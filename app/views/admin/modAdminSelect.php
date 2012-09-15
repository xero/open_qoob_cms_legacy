<div id="adminForm">
	<form name="addPage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/modAdmin/">
		<div class="row">
			<div class="lbl">Select an admin: </div>
			<div class="inputs">
				<select name="whichAdmin" class="inputs" style="border: 1px solid #666;">
					<?=$adminList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Admin " value=" Select Admin " class="btnForm" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	