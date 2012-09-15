<div id="adminForm">
	<form name="delGallery" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delGalleryCat/">
		<div class="row">
			<p>Are you sure you want to delete the gallery category: <strong><?=$name; ?></strong>?<br/>
			This action cannot be undone. 
<?
	if($subcats > 0) {
		$inflect = 'Subcategories';
		if($subcats == 1) {
			$inflect = 'Subcategory';
		}
?>
			<br/><br/><strong><?=$subcats.'&nbsp;'.$inflect; ?></strong>&nbsp;will will be deleted.
<?
	}
	if($images > 0) {
		$inflect = 'Images';
		if($images == 1) {
			$inflect = 'Image';
		}
?>
			<br/><br/><div id="imgMsg"><strong><?=$images.'&nbsp;'.$inflect; ?></strong>&nbsp;will become uncategorized.</div><br/>
<?					
	}
?>			
			<?=$deleteCheck; ?>
			</p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="gallery_cat_id" id="image_id" value="<?=$gallery_cat_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Gallery " value=" Delete Gallery " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	