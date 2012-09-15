<div id="adminForm">
	<form name="delCode" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delCode/">
		<div class="row">
			<p>Are you sure you want to delete the <strong><?=$url; ?></strong> git repository?<br/><br/>
			This action cannot be undone. <br/><br/><strong>*note*</strong><br/>This will only delete the database entry,<br/> not the actual repo on the server.
			<br/><br/></p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="git_id" id="git_id" value="<?=$git_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Git Repo " value=" Delete Git Repo " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	