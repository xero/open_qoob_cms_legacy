function goBack() {
			history.go(-1);
		}
		function selectVerify() {
			var themessage = "";

			if (document.delBlog.whichBlog.value=="x") {
				themessage += "Sorry, there are no blog posts to modify!<br/>";
			}
			if (themessage == "") {
				document.modCode.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}