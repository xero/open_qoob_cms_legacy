<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addAdmin" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/modAdmin/">
		<div class="row">
			<div class="lbl">Full Name: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtName" title="full name" value="<?=$txtName; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">User Name: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtUser" title="user name" value="<?=$txtUser; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">E-Mail: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtEmail" title="e-mail" value="<?=$txtEmail; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Password: <div style="color: #ff0000; display: inline;">*</div></div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtPass" title="password" value=""/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">
				<input type="hidden" name="admin_id" id="admin_id" value="<?=$admin_id; ?>" />
			</div>
			<div class="inputs">
				<input type="submit" name="btnMod" title=" Modify Admin " value=" Modify Admin " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
		<div class="error">* a new password must be created!</div>
	</form>
</div>
<br class="clear" />