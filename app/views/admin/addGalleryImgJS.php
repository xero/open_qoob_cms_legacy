		(function($) {
			$(document).ready(function(){
				//---load categories
				getCats();
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
					setupBMS('<?=$cats; ?>');
				}
			});
		}

		function setupBMS(cats) {
			if(cats) {
				cats = cats.split(',');
				for (var i = 0; i < cats.length; i++) {
					$('#selMainCat option[value="'+cats[i]+'"]').attr('selected', 'selected');
				}
			}
			$("#selMainCat").bsmSelect({
				title: 'Select Categories',
				removeLabel: '<strong>X</strong>',
				showEffect: function($el){ $el.fadeIn(); },
		        hideEffect: function($el){ $el.fadeOut(function(){ $(this).remove();}); },
		        plugins: [$.bsmSelect.plugins.sortable()],
		        highlight: 'highlight',
		        addItemTarget: 'original'
			});
		}

		function scrollTo(id) {
			$('html,body').animate({scrollTop:$("#"+id).offset().top},'fast');
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

		function modVerifys() {
			var themessage = "";
			
			if($("#selMainCat").val() == null) {
				themessage += "You must select at least one category!<br/>";
			}
			if (document.addGalleryImage.txtTitle.value=="") {
				themessage += "You must specify an image title!<br/>";
			}
			if (document.addGalleryImage.txtSubTitle.value=="") {
				themessage += "You must specify an image subtitle!<br/>";
			}
			if (document.addGalleryImage.theRealURL.value=="") {
				themessage += "You must specify a URL!<br/>";
			}
			if (themessage == "") {
				document.addGalleryImage.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}			
		}

		function verify() {
			var themessage = "";

			if (document.addGalleryImage.theFile.value=="") {
				themessage += "You must select an image to upload!<br/>";
			}
			if($("#selMainCat").val() == null) {
				themessage += "You must select at least one category!<br/>";
			}
			if (document.addGalleryImage.txtTitle.value=="") {
				themessage += "You must specify an image title!<br/>";
			}
			if (document.addGalleryImage.txtSubTitle.value=="") {
				themessage += "You must specify an image subtitle!<br/>";
			}
			if (document.addGalleryImage.theRealURL.value=="") {
				themessage += "You must specify a URL!<br/>";
			}
			if (themessage == "") {
				document.addGalleryImage.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}