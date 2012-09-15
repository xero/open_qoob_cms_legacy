<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addGalleryImage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/addGalleryImg/" enctype="multipart/form-data">
		<div class="row">
			<div class="lbl">File: </div>
			<div class="inputs">
				<input type="file" name="theFile" id="theFile" accept="image/*" /> 
			</div>
		</div>
		<div class="row">
			<div class="lbl">Gallery: </div>
			<div class="inputs">
				<div id="galleryCat">loading categories...</div>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Title: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtTitle" title="Image Title" maxlength="300" value="<?=$txtTitle; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">SubTitle: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSubTitle" title="Image SubTitle" maxlength="300" value="<?=$txtSubTitle; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">URL: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtURL" id="txtURL" title="URL" maxlength="50" value="<?=$txtURL; ?>" onkeyup="urlInflector()"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">&nbsp;</div>
			<div class="inputs">
				<input type="radio" name="inflection" value="camel" onchange="urlInflector()" /> camel case
				<br class="clear" />
				<input type="radio" name="inflection" checked="checked" value="underscore" onchange="urlInflector()" /> underscored
			</div>
		</div>
		<div class="row">
			<div class="qoobformrow">
				<div class="bubble">
					<div id="URLexample"><?=QOOB_DOMAIN; ?>projects/<?=$txtURL; ?></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Excerpt: </div>
			<textarea class="inputwiderow textArearow" name="txtExcerpt" title="Excerpt" maxlength="1000"><?=$txtExcerpt; ?></textarea>
		</div>
		<div class="row">
			<div class="lbl">Description: </div>
			<textarea class="inputwiderow textArearow" name="txtDescript" title="Description"><?=$txtDescript; ?></textarea>
		</div>
		<div class="row">
			<div class="lbl">Live: </div>
			<div class="inputs">
<?php
	if($chkLive == 1) {
?>
			<label><input type="checkbox" name="chkLive" title="Visible on the site" checked="checked" /> Yes</label>
<?php
	} else {
?>
			<label><input type="checkbox" name="chkLive" title="Visible on the site" /> Yes</label>
<?php
	}
?>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnAdd" title=" Add Image " value=" Add Image " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
		<input type="hidden" name="theRealURL" id="theRealURL" value="<?=$txtURL; ?>" />
	</form>
</div>
<br class="clear" /><br/>