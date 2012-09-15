<div id="adminForm">
	<form name="delBlog" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/delBlog/">
		<div class="row">
			<p>Are you sure you want to delete the <strong><?=$url; ?></strong> blog entry?<br/>
			This action cannot be undone. 
			<br/><br/></p>
		</div>
		<div class="row">
			<p>
				<input type="hidden" name="action" id="action" value="del" />
				<input type="hidden" name="post_id" id="post_id" value="<?=$post_id; ?>" />
				<input type="button" name="btnCancel" title=" Cancel " value=" Cancel " class="btnForm" onclick="goBack();" />
				<input type="submit" name="btnDelete" title=" Delete Blog " value=" Delete Blog " class="btnForm" />
			</p>
		</div>
	</form>
</div>
<br class="clear" />	