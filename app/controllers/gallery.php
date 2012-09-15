<?php
/**
 * gallery controller
 * class to render image galleries, but in this case it's my portfolio.
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
class gallery extends controller {
	/**
	 * index function
	 * display galleries and/or images
	 */	
	public function index() {
		$html["title"] = 'portfolio';
		$html["meta"] = '<link rel="stylesheet" type="text/css" id="shadow-css" href="'.BASE_URL.'style/css/shadowbox.css" media="screen"/>';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = 'gallery';
		$html["script"] = $this->view('initShadowboxJS', array(), true);
		$html["jsfiles"] = '<script type="text/javascript" src="'.BASE_URL.'style/js/shadowbox.js" charset="utf-8"></script>';

		$gm = $this->model("galleryModel");

		if(isset(library::catalog()->url[1])) {
			if(!isset(library::catalog()->url[2])) {
				//---display category
				$cat = $gm->getCat(library::catalog()->url[1]);
				if(!isset($cat[0])) {
					throw new Exception("invalid category", statusCodes::HTTP_NOT_FOUND);
				} else {
					$body = '<h1><a href="'.QOOB_DOMAIN.'portfolio/'.$cat[0]['url'].'">'.$cat[0]['name'].'</a></h1>'.$cat[0]['excerpt'].'<br/><br/>';
					$subCats = $gm->getSubCats($cat[0]['gallery_cat_id']);
					if(isset($subCats[0])) {
						foreach ($subCats as $kitten) {
							$body .= '<div class="projectBox"><a href="'.QOOB_DOMAIN.'portfolio/'.$cat[0]['url'].'/'.$kitten['url'].'"><img src="'.BASE_URL.'style/img/projects/'.$kitten['url'].'_icon.png" class="floatLeft banner" alt="'.$kitten['name'].'" /></a><strong><a href="'.QOOB_DOMAIN.'portfolio/'.$cat[0]['url'].'/'.$kitten['url'].'">'.$kitten['name'].'</a></strong><br/>'.$kitten['excerpt'].'</div><br class="clear"/>';
						}
					}
				}
			} else {
				if(!isset(library::catalog()->url[3])) {
					//---display project
					$proj = $gm->getCat(library::catalog()->url[2]);
					if(!isset($proj[0])) {
						throw new Exception("invalid project", statusCodes::HTTP_NOT_FOUND);
					} else {
						$body = '<div class="projectBox"><img src="'.BASE_URL.'style/img/projects/'.$proj[0]['url'].'_banner.png" class="banner" alt="'.$proj[0]['name'].'" /><h1>'.$proj[0]['name'].'</h1><br/>'.html_entity_decode($proj[0]['description']).'<br/><br/></div>';
						$imgs = $gm->getCatImgs($proj[0]['gallery_cat_id']);
						if(isset($imgs)) {
							foreach ($imgs as $img) {
								$extension_pos = strrpos($img['filename'], '.');
								$thumb = substr($img['filename'], 0, $extension_pos) . '_thumb' . substr($img['filename'], $extension_pos);
								$body .= '<div class="imgBox floatLeft"><a rel="shadowbox[gallery-'.$proj[0]['name'].'];player=img;" href="'.BASE_URL.'style/img/projects/'.$img['filename'].'"><img src="'.BASE_URL.'style/img/projects/'.$thumb.'" alt="'.$img['title']." ".$img['subtitle'].'" title="'.$img['title']." ".$img['subtitle'].'" /></a><br/>&nbsp;<strong><a href="'.QOOB_DOMAIN.'portfolio/'.library::catalog()->url[1].'/'.library::catalog()->url[2].'/'.$img['url'].'">more info</a></strong></div>';
							}
						}
					}
				} else {
					//---display image
					$img = $gm->getImg(library::catalog()->url[3]);
					if(!isset($img[0])) {
						throw new Exception("invalid image", statusCodes::HTTP_NOT_FOUND);
					} else {
						$html["sidebar"] = '';
						$body = '<div class="projectBox"><h1>'.$img[0]['title'].' '.$img[0]['subtitle'].'</h1>'.html_entity_decode($img[0]['description']).'</div></div></div></div><a href="'.BASE_URL.'style/img/projects/'.$img[0]['filename'].'"><img src="'.BASE_URL.'style/img/projects/'.$img[0]['filename'].'" alt="'.$img[0]['title']." ".$img[0]['subtitle'].'" title="'.$img[0]['title']." ".$img[0]['subtitle'].'" border="0" /></a><div><div><div>';
					}
				}
			}
		} else {
			//---list projects
			$mainCats = $gm->getMainCats();
			
			$body = '';
			if(!isset($mainCats[0])) {
				$body = 'no categories to display.';
			} else {
				foreach ($mainCats as $cat) {
					$body .= '<h1><a href="'.QOOB_DOMAIN.'portfolio/'.$cat['url'].'">'.$cat['name'].'</a></h1>'.$cat['excerpt'].'<br/><br/>';
					$subCats = $gm->getSubCats($cat['gallery_cat_id']);
					if(isset($subCats[0])) {
						foreach ($subCats as $kitten) {
							$body .= '<div class="projectBox"><a href="'.QOOB_DOMAIN.'portfolio/'.$cat['url'].'/'.$kitten['url'].'"><img src="'.BASE_URL.'style/img/projects/'.$kitten['url'].'_icon.png" class="floatLeft banner" alt="'.$kitten['name'].'" /></a><strong><a href="'.QOOB_DOMAIN.'portfolio/'.$cat['url'].'/'.$kitten['url'].'">'.$kitten['name'].'</a></strong><br/>'.$kitten['excerpt'].'</div><br class="clear"/>';
						}
					}
				}
			}
		}

		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Gallery',
			'subtitle' => '',
			'content' => $body,
			'comments' => 0
		);
		$html["body"] = $this->view("post", $post, true);

		$this->view("pixelgraff", $html);
	}
}

?>