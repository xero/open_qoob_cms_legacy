<div id="adminForm">
	<form name="delPage" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delAdmin/">
		<div class="row">
			<p>Are you sure you want to delete the admin: <strong><?=$name; ?></strong>?<br/>
			This action cannot be undone. 
			<br/><br/></p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="admin_id" id="admin_id" value="<?=$admin_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Admin " value=" Delete Admin " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	