<div id="adminForm">
	<form name="delPage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delPage/">
		<div class="row">
			<p>Are you sure you want to delete the <strong><?=$url; ?></strong> page?<br/>
			This action cannot be undone. 
			<br/><br/></p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="page_id" id="page_id" value="<?=$page_id; ?>" />
				<input type="hidden" name="route_id" id="route_id" value="<?=$route_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Page " value=" Delete Page " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	