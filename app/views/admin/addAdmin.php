<div id="formErrors"><?=$errors; ?></div>
<div id="adminForm">
	<form name="addAdmin" method="post" action="<?=QOOB_DOMAIN; ?>backdoor/addAdmin/">
		<div class="row">
			<div class="lbl">Full Name: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtName" lbl="full name" value="<?=$txtName; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">User Name: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtUser" lbl="user name" value="<?=$txtUser; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">E-Mail: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtEmail" lbl="e-mail" value="<?=$txtEmail; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl">Password: </div>
			<div class="inputs">
				<input class="inputrow" type="text" name="txtPass" lbl="password" value="<?=$txtPass; ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="lbl"></div>
			<div class="inputs">
				<input type="submit" name="btnAdd" lbl=" Add Admin " value=" Add Admin " class="btnForm" onclick="return verify()"/>
			</div>
		</div>
	</form>
</div>
<br class="clear" />