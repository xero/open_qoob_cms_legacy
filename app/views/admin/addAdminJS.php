	function verify() {
				var emailreg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
				var themessage = "";
				if (document.addAdmin.txtName.value=="") {
					themessage += "You must specify the user's full name! <br/>";
				}
				if (document.addAdmin.txtUser.value=="") {
					themessage += "You must specify a username for login! <br/>";
				}
				if (document.addAdmin.txtEmail.value=="") {
					themessage += "You must specify an email address! <br/>";
				}
				if (emailreg.test(document.addAdmin.txtEmail.value) == false) {
					themessage += "Your email address is invalid! <br/>";
				}
				if (document.addAdmin.txtPass.value=="") {
					themessage += "You must specify a password! <br/>";
				}

				if (themessage == "") {
					document.addAdmin.submit();
				} else {
					//alert(themessage);
					$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
					return false;
				}
			}
