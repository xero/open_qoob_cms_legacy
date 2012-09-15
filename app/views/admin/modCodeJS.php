		function goBack() {
			history.go(-1);
		}
		function verify() {
			var themessage = "";

			if (document.modCode.whichRepo.value=="x") {
				themessage += "Sorry, there are no repos to modify!<br/>";
			}
			if (themessage == "") {
				document.modCode.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}
