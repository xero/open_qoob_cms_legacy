<?=$errors; ?>
<div id="adminForm">
	<form method="post" action="<?=QOOB_DOMAIN; ?>backdoor/">
		<div class="row">
			<div class="lbl">Username: </div>
			<div class="inputs">
				<input type="text" name="txtUser" title="username" value=""/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Password: </div>
			<div class="inputs">
				<input type="password" name="txtPass" title="password" value=""/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnLogin" value="  Login  "/>
			</div>
		</div>
	</form>
</div>
<br/><br class="clear"/>