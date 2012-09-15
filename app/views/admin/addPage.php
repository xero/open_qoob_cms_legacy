<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addPage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/addPage/">
		<div class="row">
			<div class="lbl">Page Title: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtTitle" title="page title" value="<?=$txtTitle; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">SubTitle: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSubTitle" title="subtitle" value="<?=$txtSubTitle; ?>"/>
			</div>
		</div>	
		<div class="row">
			<div class="lbl">URL: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtURL" id="txtURL" title="URL" value="<?=$txtURL; ?>" onkeyup="urlInflector()"/>
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
					<div id="URLexample"><?=QOOB_DOMAIN; ?><?=$txtURL; ?></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Content: </div>
			<div class="inputs">
				<textarea class="inputrow textArearow" name="txtBody" title="Body"><?=$txtBody; ?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Javascript: </div>
			<div class="inputs">
				<textarea class="inputrow textArearow" name="txtScript" title="Script"><?=$txtScript; ?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Menu: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSelected" title="Selected menu item" value="<?=$txtSelected; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Meta: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtMeta" title="html meta data" value="<?=$txtMeta; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Sidebar: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSidebar" title="Selected menu item" value="<?=$txtSidebar; ?>"/>
			</div>
		</div>

		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnAdd" title=" Add Page " value=" Add Page " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
		<input type="hidden" name="theRealURL" id="theRealURL" value="<?=$txtURL; ?>" />
	</form>
</div>
<br class="clear" />