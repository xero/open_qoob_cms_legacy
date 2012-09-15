		(function($) {
			$(document).ready(function(){
				//---load categories
				getCats();
			});
		})(jQuery);

		function getCats() {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=getGalleryCategories&type=all",
				cache: false,
				success: function(html){
					$("#galleryCat").html(html);
					if(parent) {
						$('#selMainCat option[value="'+parent+'"]').attr('selected', 'selected');
					}
				}
			});
		}

		function selectCat() {
			if($("#whichGallery").val() != 0) {
				id = $("#whichGallery").val();
				$.ajax({
					type: "POST",
					url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
					data: "action=getGalleryImages&cat_id="+id,
					cache: false,
					success: function(html){
						$("#imgList").html('<div class="row"><div class="lbl">Select an Image: </div><div class="inputs">'+html+'<br/><br/></div></div>');
					}
				});
			}
		}

		function goBack() {
			history.go(-1);
		}

		function scrollTo(id) {
			$('html,body').animate({scrollTop:$("#"+id).offset().top},'fast');
		}

		function selectVerify() {
			var themessage = "";
			if(parseFloat(document.selectImg.whichGallery.selectedIndex) < 1) {
				themessage += "You must select a category!<br/>";
			} else {
				if(parseFloat(document.selectImg.selectImgID.value) < 1) {
					themessage += "You must select an image!<br/>";
				}
			}
			if(themessage == "") {
				document.selectImg.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}