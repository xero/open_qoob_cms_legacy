<div id="formErrors"></div>
<div id="adminForm">
	<form name="delGalleryCat" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delGalleryCat/">
		<div class="row">
			<div class="lbl">Select a Gallery: </div>
			<div class="inputs">
				<select name="whichGallery" class="inputs" style="border: 1px solid #666;">
					<?=$galleryList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Gallery " value=" Select Gallery " class="btnForm" onclick="return verify();" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	