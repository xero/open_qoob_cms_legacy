	function urlInflector() {
				var str = $("#txtURL").val();
				if(str != "") {
					$.ajax({
						type: "POST",
						url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
						data: "action=inflection&type="+$("input:radio[name=inflection]:checked").val()+"&str="+str,
						cache: false,
						success: function(html){
							$("#URLexample").html("<?=QOOB_DOMAIN; ?>"+html);
							$("#theRealURL").val(html);
						}
					});
				}
			}
			function verify() {
				var themessage = "";
				if (document.addRepo.txtRepo.value=="") {
					themessage += "You must specify a repo location! <br/>";
				}
				if (document.addRepo.txtName.value=="") {
					themessage += "You must specify a repo name! <br/>";
				}
				if (document.addRepo.txtDescription.value=="") {
					themessage += "You must specify a short description! <br/>";
				}
				if (document.addRepo.txtReadMe.value=="") {
					themessage += "You must specify some content! <br/>";
				}				
				if (document.addRepo.theRealURL.value=="") {
					themessage += "You must specify a page URL! <br/>";
				}
				if (document.addRepo.theRealURL.value=="error") {
					themessage += "You have an error in your URL! <br/>";
				}

				if (themessage == "") {
					document.addRepo.submit();
				} else {
					//alert(themessage);
					$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
					return false;
				}
			}
