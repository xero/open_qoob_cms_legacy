<div id="adminForm">
	<form name="modPage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/modPage/">
		<div class="row">
			<div class="lbl">Select a page: </div>
			<div class="inputs">
				<select name="whichPage" class="inputs" style="border: 1px solid #666;">
					<?=$pageList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Page " value=" Select Page " class="btnForm" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	