<div id="formErrors"></div>
<div id="adminForm">
	<form name="delCode" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delCode/">
		<div class="row">
			<div class="lbl">Select a repo: </div>
			<div class="inputs">
				<select name="whichRepo" class="inputs" style="border: 1px solid #666;">
					<?=$repoList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Git Repo " value=" Select Git Repo " class="btnForm" onclick="return verify()" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	