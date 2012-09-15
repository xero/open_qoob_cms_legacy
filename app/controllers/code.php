<?php
/**
 * code controller
 * class to visualize the code in git repos
 *
 * @author xero harrison <x@xero.nu>
 * @copyright (cc) creative commons - attribution-shareAlike 3.0 unported
 * @version 1.0
 * @package app
 * @subpackage controllers
 */
class code extends controller {
	/**
	 * index function
	 * depending on the url this function can: 
	 * - display all the git repositories
	 * - display trees/blobs
	 * - display commit history 
	 * - export zip/tarballs of files
	 */
	public function index() {
		$html["title"] = 'code';
		$html["meta"] = '';
		$html["sidebar"] = $this->view("blog/sidebar_qr", array(), true);
		$html["selected"] = 'code';
		$html["script"] = '';
		$html["body"] = '';

		$urls = library::catalog()->url;
		$cm = $this->model("codeModel");

		if(!isset($urls[1])) {
			//---list all repos
			$repos = $cm->getRepos();

			if(!isset($repos[0])) {
				$html["body"] = 'No git repositories found...';
			} else {
				foreach ($repos as $repo) {
					$html["body"] .= '<div class="projectBox"><h1><img class="gitIcons" src="'.BASE_URL.'style/img/git/repo.png" alt="git repo icon" /><a href="'.QOOB_DOMAIN.'code/'.$repo["url"].'">'.$repo['name'].'</a></h1>'.html_entity_decode($repo["description"]).'<br/><br/></div>';
				}
			}			
		} else {
			//---decode url
			$repo 	= (isset($urls[1])) ? filter_var($urls[1], FILTER_SANITIZE_URL) : '';
			$branch = (isset($urls[2])) ? filter_var($urls[2], FILTER_SANITIZE_URL) : 'master';
			$commit = (isset($urls[3])) ? filter_var($urls[3], FILTER_SANITIZE_URL) : '';
			$type 	= (isset($urls[4])) ? filter_var($urls[4], FILTER_SANITIZE_URL) : 'tree';
			$objid 	= (isset($urls[5])) ? filter_var($urls[5], FILTER_SANITIZE_URL) : '';
			$export = (isset($urls[6])) ? filter_var($urls[6], FILTER_SANITIZE_URL) : '';
			$commitName = '';
			if($commit == 'history') {
				$type = 'history';
			}

			//---get info from db
			$repoInfo = $cm->getRepo($repo);
			if(!isset($repoInfo[0])) {
				throw new Exception("unknown repository", statusCodes::HTTP_NOT_FOUND);
			}
			
			//---load GLiP
			$this->library(qoob_types::utility, 'git');
			$this->git->init(QOOB_ROOT.'/repos/'.$repoInfo[0]["repo"]);
			
			//---check branch name
			if(!in_array($branch, $this->git->branches)) {
				throw new Exception("unknown branch", statusCodes::HTTP_NOT_FOUND);
			}

			//---html branch select	
			$branches = '<select name="branch" onchange="go(\'branch\')">';
			foreach ($this->git->branches as $theBranch) {
				if($theBranch == $branch) {
					$branches .=  '<option value="'.$theBranch.'" selected="selected">'.$theBranch.'</option>';
				} else {
					$branches .=  '<option value="'.$theBranch.'">'.$theBranch.'</option>';
				}				
			}
			$branches .=  '</select>';

			//---list history
			$gitcommit = $this->git->getTip($branch);
			$obj = $this->git->getObject($gitcommit);
			$hist = $obj->getHistory();
			$hist = array_reverse($hist);

			$found = false;
			$commits = '<select name="commit" onchange="go(\'commit\')">';
			foreach ($hist as $event) {
				if(sha1_hex($event->name) == $commit) {
					$commitName = sha1_hex($event->name);
					$commits .= '<option value="'.sha1_hex($event->name).'" selected="selected">'.date('m/d/y g:ia', $event->committer->time).'</option>';
					$found = true;
				} else {
					$commits .= '<option value="'.sha1_hex($event->name).'">'.date('m/d/y g:ia', $event->committer->time).'</option>';					
				}
			}
			if($commit == '') {
				$commitName = sha1_hex($hist[0]->name);					
			}
			$commits .=  '</select>&nbsp;&nbsp;<a href="'.QOOB_DOMAIN.'code/'.$urls[1].'/'.$branch.'/history/'.'">view history</a>';
			//---check commit id
			if($commit != '' && $commit != 'history') {
				if(!$found) {
					throw new Exception("unknown commit id", statusCodes::HTTP_NOT_FOUND);
				}
			}
			$dl = QOOB_DOMAIN.'code/'.$urls[1].'/'.$branch.'/'.$commitName.'/archive/';
			$fileName = ($type == 'tree') ? 'Files' : 'File';
			if($type == 'tree') {
				if($objid != '') {
					$tree = $this->git->getObject(sha1_bin($objid));
					$dl .= $objid;
				} else {
					if($commit != '') {
						$gitcommit = $this->git->getObject(sha1_bin($commit));
						$tree = $gitcommit->getTree();
						$dl .= sha1_hex($tree->name);
					} else {
						$gitcommit = $this->git->getObject(sha1_bin($commitName));
						$tree = $gitcommit->getTree();
						$dl .= sha1_hex($tree->name);
					}
				}

				$fileName = 'Files';
				$url = QOOB_DOMAIN.'code/'.$urls[1].'/'.$branch.'/'.$commitName.'/';

				$ord = array();
				foreach ($tree->nodes as $file) {
					if($file->is_dir) {
						$ord[] = '<a href="'.$url.'tree/'.sha1_hex($file->object).'">/'.$file->name.'/</a>';
					}
				}
				foreach ($tree->nodes as $file) {
					if(!$file->is_dir) {
						$ord[] = '&nbsp;<a href="'.$url.'blob/'.sha1_hex($file->object).'">'.$file->name.'</a>';
					}
				}
				$files = '<pre>';
				foreach ($ord as $file) {
					$files .= $file.'<br/>';
				}
				$files .= '</pre><div class="floatRight"><img class="gitIcons" src="'.BASE_URL.'style/img/git/commit-alt.png" alt="download" />Download:&nbsp;<a href="'.$dl.'/zip">zip</a>&nbsp;/&nbsp;<a href="'.$dl.'/tarball">tar.gz</a>&nbsp;&nbsp;&nbsp;</div><br class="clear"/>';
			} else if($type == 'blob') {
				$wrapper = '<pre>';
				$gitcommit = $this->git->getObject(sha1_bin($commit));
				$tree = $gitcommit->getTree()->listRecursive();
				$langs = array('cgi', 'pl', 'cs', 'xml', 'xhtml', 'xslt', 'html', 'htm', 'mxml', 'sql', 'mysql', 'mssql', 'db', 'php', 'php5', 'as3', 'as', 'css', 'js');
				foreach ($tree as $name => $sha) {
					if(sha1_hex($sha) == $objid) {
						$fileName = 'File: '.$name;
						$ext = substr(strrchr($name,'.'),1);
					}
				}
				$wrapper = (in_array($ext, $langs)) ? '<pre class="brush: '.$ext.'">' : '<pre class="brush: text">';
				$blob = $this->git->getObject(sha1_bin($objid));
				if(is_ascii($blob->data) ) {
					$files = $wrapper.htmlentities($blob->data).'</pre>';
					$html['jsfiles'] = '<script type="text/javascript" src="'.BASE_URL.'style/js/syntaxHighlighter.js" charset="utf-8"></script>'.PHP_EOL.'<link rel="stylesheet" type="text/css" id="syntax-core-css" href="'.BASE_URL.'style/css/shCoreDefault.css" media="all"/>'.PHP_EOL.'<link rel="stylesheet" type="text/css" id="syntax-default-css" href="'.BASE_URL.'style/css/shThemeDefault.css" media="all"/>'.PHP_EOL;
				} else {
					switch($ext) {
						case 'jpg':
							$files = '<img src="data:image/jpeg;base64,'.base64_encode($blob->data).'" alt="image from git repo" class="gitIcons" />';
						break;
						case 'png':
							$files = '<img src="data:image/png;base64,'.base64_encode($blob->data).'" alt="image from git repo" class="gitIcons" />';
						break;
						case 'gif':
							$files = '<img src="data:image/gif;base64,'.base64_encode($blob->data).'" alt="image from git repo" class="gitIcons" />';
						break;
						case 'bmp':
							$files = '<img src="data:image/bmp;base64,'.base64_encode($blob->data).'" alt="image from git repo" class="gitIcons" />';
						break;
						default: 
							$files = '<p>This file is not ascii formatted, so cannot be displayed. sorry :(<br/>clone the git repo and get a copy.</p>';
						break;
					}
				}
			} else if($type == 'archive') {
				if($export == 'zip' || $export == 'tarball') {
					$archive = $this->git->archive($repoInfo[0]["repo"].'-'.substr($objid, 0, 7), QOOB_ROOT.'/repos/'.$repoInfo[0]["repo"], $objid, $export);
					die($archive);
				} else {
					throw new Exception("unknown compression method", statusCodes::HTTP_NOT_FOUND);
				}
			} else {
				$fileName = 'History';
				$files = '<br/>';
				foreach ($hist as $event) {
					$files .= 'sha1: <a href="'.QOOB_DOMAIN.'code/'.$urls[1].'/'.$branch.'/'.sha1_hex($event->name).'">'.sha1_hex($event->name).'</a><br/>committer: '.$event->committer->name.' &lt;'.$event->committer->email.'&gt; <br/>date: '.date('m/d/y', $event->committer->time).'<br/>'.wordwrap($event->summary, 90).'<br/><br/>';
				}
				$files .= '<div class="floatRight gitFeeds"><a href="'.QOOB_DOMAIN.'feeds/rss/code/'.$urls[1].'/'.$branch.'"><img src="'.BASE_URL.'style/img/feeds-rss.png" alt="rss feed" title="rss feed" width="20" height="20" /></a><a href="'.QOOB_DOMAIN.'feeds/atom/code/'.$urls[1].'/'.$branch.'"><img src="'.BASE_URL.'style/img/feeds-atom.png" alt="atom feed" title="atom feed" width="20" height="20" /></a></div><br class="clear"/>';
			}

			$html["body"] .= '<form name="git" action="#"><div class="projectBox"><h1><img class="gitIcons" src="'.BASE_URL.'style/img/git/repo.png" alt="repo" />'.$repoInfo[0]['name'].'</h1><h4>'.$repoInfo[0]['subtitle'].'</h4><br/>
			<div id="cloneBox"><div class="msg">Clone</div>&nbsp;&nbsp;<input type="text" style="width: 500px;" value="'.QOOB_DOMAIN.'repos/'.$repoInfo[0]["repo"].'" /></div><br/>
			<div id="branchBox"><div class="msg">Branches</div><br/>'.$branches.'</div>
			<div id="commitBox"><div class="msg">Commits</div><br/>'.$commits.'</div><br class="clear"/><br/>
			<div id="filesBox"><div class="msg">&nbsp;'.$fileName.'</div><br/>'.$files.'</div><br/>	
			<div id="readmeBox"><div class="msg">Read Me</div><br/><p>'.html_entity_decode($repoInfo[0]["readme"]).'</p><br/></div></div></form>';

			$html["script"] = $this->view("gitJS", array('URL' => QOOB_DOMAIN.'code/'.library::catalog()->url[1].'/'), true);
		}
		$post = array(
			'mainCat' => '',
			'url' => '',
			'title' => 'Code',
			'subtitle' => 'GIT repositories',
			'content' => $html["body"],
			'comments' => 0
		);
		$html["body"] = $this->view("post", $post, true);

		$this->view("pixelgraff", $html);
	}
}

?>