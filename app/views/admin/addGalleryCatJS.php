		(function($) {
			$(document).ready(function(){
				//---load categories
				getCats('<?=$parent; ?>');
			     //---run url check
			     urlInflector();
			});
		})(jQuery);
		
		function urlInflector() {
			var str = $("#txtURL").val();
			if(str != "") {
				$.ajax({
					type: "POST",
					url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
					data: "action=inflection&type="+$("input:radio[name=inflection]:checked").val()+"&str="+str,
					cache: false,
					success: function(html){
						$("#URLexample").html("<?=QOOB_DOMAIN; ?>projects/"+html);
						$("#theRealURL").val(html);
					}
				});
			}
		}

		function getCats(parent) {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=getGalleryCategories&type=new",
				cache: false,
				success: function(html){
					$("#galleryCat").html(html);
					if(parent) {
						$('#selNewCat option[value="'+parent+'"]').attr('selected', 'selected');
					}					
				}
			});
		}

		function scrollTo(id) {
			$('html,body').animate({scrollTop:$("#"+id).offset().top},'fast');
		}

		function verify() {
			var themessage = "";
			if (document.addBlog.txtName.value=="") {
				themessage += "You must specify a gallery name!<br/>";
			}
			if (document.addBlog.txtTitle.value=="") {
				themessage += "You must specify a title!<br/>";
			}
			if (document.addBlog.theRealURL.value=="") {
				themessage += "You must specify a URL!<br/>";
			}
			if (themessage == "") {
				document.addBlog.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}