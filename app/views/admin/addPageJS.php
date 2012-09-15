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
				if (document.addPage.txtTitle.value=="") {
					themessage += "You must specify a page title! <br/>";
				}
				if (document.addPage.txtSubTitle.value=="") {
					themessage += "You must specify a page subtitle! <br/>";
				}
				if (document.addPage.txtBody.value=="") {
					themessage += "You must specify some page content! <br/>";
				}
				if (document.addPage.theRealURL.value=="") {
					themessage += "You must specify a page URL! <br/>";
				}
				if (document.addPage.theRealURL.value=="error") {
					themessage += "You have an error in your URL! <br/>";
				}

				if (themessage == "") {
					document.addPage.submit();
				} else {
					//alert(themessage);
					$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
					return false;
				}
			}
