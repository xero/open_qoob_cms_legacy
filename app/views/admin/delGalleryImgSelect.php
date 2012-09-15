<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="selectImg" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delGalleryImg/">
		<div class="row">
			<div class="lbl">Select a Gallery: </div>
			<div class="inputs">
				<select name="whichGallery" id="whichGallery" class="inputs" onchange="selectCat()">
					<option value="0">Select Category...</option>
					<?=$galleryList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div id="imgList"></div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Image " value=" Select Image " class="btnForm" onclick="return selectVerify()" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	