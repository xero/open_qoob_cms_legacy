		function goBack() {
			history.go(-1);
		}
		function checkChange(count) {
			var inflect = 'Images';
			if(count == 1) {
				inflect = 'Image';
			}
			var msg = '';
			var color = '#000';
			if($("#chkDelete").is(':checked')) {
				msg = 'will be deleted';
				color = '#ff0000';
			} else {
				msg = 'will become uncategorized';
				color = '#000';
			}
			$("#imgMsg").html('<font color="'+color+'"><strong>'+count+'&nbsp;'+inflect+'</strong>&nbsp;'+msg+'</font>');
		}
		function verify() {
			var themessage = "";

			if (document.delGalleryCat.whichGallery.value=="x") {
				themessage += "Sorry, there are no galleries to delete!<br/>";
			}
			if (themessage == "") {
				document.delGalleryCat.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}

