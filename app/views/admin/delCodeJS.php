		function goBack() {
			history.go(-1);
		}
		function verify() {
			var themessage = "";

			if (document.delCode.whichRepo.value=="x") {
				themessage += "Sorry, there are no repos to delete!<br/>";
			}
			if (themessage == "") {
				document.delCode.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}
