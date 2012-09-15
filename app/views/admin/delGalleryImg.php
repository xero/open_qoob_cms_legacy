<div id="adminForm">
	<form name="delImg" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delGalleryImg/">
		<div class="row">
			<p>Are you sure you want to delete the image: <strong><a href="<?=BASE_URL;?>style/img/projects/<?=$theFile; ?>"><?=$theFile; ?></a></strong>?<br/>
			This action cannot be undone. 
			<br/><br/></p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="image_id" id="image_id" value="<?=$image_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Image " value=" Delete Image " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	