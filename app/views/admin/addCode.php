<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addRepo" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/addCode/">
		<div class="row">
			<div class="lbl">Repository: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtRepo" title="Repo Location" value="<?=$txtRepo; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Name: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtName" title="Repo Name" value="<?=$txtName; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">SubTitle: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtSubTitle" title="Subtitle" value="<?=$txtSubTitle; ?>"/>
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
			<div class="lbl">Description: </div>
			<div class="inputs">
				<textarea class="inputrow textArearow" name="txtDescription" title="Description"><?=$txtDescription; ?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Read Me: </div>
			<div class="inputs">
				<textarea class="inputrow textArearow" name="txtReadMe" title="Read Me"><?=$txtReadMe; ?></textarea>
			</div>
		</div>

		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnAdd" title=" Add Git Repo " value=" Add Git Repo " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
		<input type="hidden" name="theRealURL" id="theRealURL" value="<?=$txtURL; ?>" />
	</form>
</div>
<br class="clear" />