<div id="formErrors"></div>
<div id="adminForm">
	<form name="modBlog" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/modBlog/">
		<div class="row">
			<div class="lbl">Select Blog Post: </div>
			<div class="inputs">
				<select name="whichBlog" class="inputs" style="border: 1px solid #666;">
					<?=$blogList; ?>
				</select>
				<br/><br/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="hidden" name="action" id="action" value="load" />
				<input type="submit" name="btnSelect" title=" Select Blog " value=" Select Blog " class="btnForm" onclick="return selectVerify()" />
			</div>
		</div>
	</form>
</div>
<br class="clear" />	