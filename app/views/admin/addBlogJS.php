		(function($) {
			$(document).ready(function(){
				//---load categories
				getCats();
				//---load tags
				getTags();
				//---init calendar
				$('#txtDateTime').datepicker({
					duration: '',
			        showTime: true,
			        constrainInput: false
			     });
			     //---run url check
			     urlInflector();
			     //---setup post options
			     toggleDate('<?=$post; ?>');
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
						$("#URLexample").html("<?=QOOB_DOMAIN; ?>blog/"+html);
						$("#theRealURL").val(html);
					}
				});
			}
		}

		function tagCatInflector(callback) {
			str = $("#"+callback).val();
			if(str != "") {
				$.ajax({
					type: "POST",
					url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
					data: "type=underscore&str="+str+"&action=inflection",
					cache: false,
					success: function(html){
						$("#"+callback).val(html);
					}
				});
			}
		}

		function getCats() {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=getCategories",
				cache: false,
				success: function(html){
					$("#blogCat").html(html);
					setupBMS('<?=$catlist; ?>');
				}
			});
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=getCategories&type=new",
				cache: false,
				success: function(html){
					$("#newBlogCat").html(html);
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
		
		function addCat() {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "name="+$("#txtCatName").val()+"&url="+$("#txtCatURL").val()+"&parent="+$("#selNewCat").val()+"&action=addCategory",
				cache: false,
				success: function(html){
					if(html=="success") {
						alert('Success!\nNew category created.');
						clearForm("cats");
						closeForm("cats");
						getCats();
					} else if(html=="used") {
						alert('Error!\nThat category already exists.');
					} else if(html=="missing") {
						alert('Error!\nMissing required fields.');
					} else {
						alert('Error?\n'+html);
					}
				}
			});
		}

		function getTags() {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=getTags",
				cache: false,
				success: function(html){
					$("#tagCloud").html(html);
					initTags('<?=$taglist; ?>');
				}
			});
		}

		function addTag() {
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "name="+$("#txtTagName").val()+"&url="+$("#txtTagURL").val()+"&action=addTag",
				cache: false,
				success: function(html){
					if(html=="success") {
						alert('Success!\nNew tag created.');
						clearForm("tags");
						closeForm("tags");
						getTags();
					} else if(html=="used") {
						alert('Error!\nThat tag already exists.');
					} else if(html=="missing") {
						alert('Error!\nMissing required fields.');
					} else {
						alert('Error?\n'+html);
					}
				}
			});
		}
		
		function tagit(tag) {
			var tags = $("#txtTags").val();
			tags = tags.split(',');
			//prune null elements
			tags = $.grep(tags,function(n){
			    return(n);
			});
			if(jQuery.inArray(tag, tags) > -1) {
				tags.splice(tags.indexOf(tag), 1);
				$("#tag"+tag).css("color", "#ccc");
				$("#tag"+tag).css("border-bottom", "none");
			} else {
				tags.push(tag);
				$("#tag"+tag).css("color", "#fff");
				$("#tag"+tag).css("border-bottom", "1px solid #52BB4A");
			}
			tags.join(',');
			$("#txtTags").val($.trim(tags));
		}

		function toggleDate(what) {
			switch(what) {
				case "date":
					$("#pickDate").fadeIn();			
				break;
				default:
					$("#pickDate").fadeOut();
				break;
			}
		}			
		
		function openForm(what) {
			switch(what) {
				case "cats":
					$("#blogCats").fadeOut();
					$("#newCats").fadeIn();			
				break;
				case "tags":
					$("#blogTags").fadeOut();
					$("#newTags").fadeIn();			
				break;
			}
		}		
		
		function closeForm(what) {
			switch(what) {
				case "cats":
					$("#blogCats").fadeIn();
					$("#newCats").fadeOut();			
				break;
				case "tags":
					$("#blogTags").fadeIn();
					$("#newTags").fadeOut();			
				break;
			}
		}		

		function clearForm(what) {
			switch(what) {
				case "cats":
					$("#txtCatName").val("");
					$("#txtCatURL").val("");
					$("#selNewCat").val(0);
				break;
				case "tags":
					$("#txtTagName").val("");
					$("#txtTagURL").val("");
				break;
			}
		}

		function initTags(tags) {
			if(tags) {
				tags = tags.split(',');
				for (var i = 0; i < tags.length; i++) {
					tagit(tags[i]);
				}
			}
		}

		function scrollTo(id) {
			$('html,body').animate({scrollTop:$("#"+id).offset().top},'fast');
		}

		function verify() {
			var themessage = "";
			if (document.addBlog.txtTitle.value=="") {
				themessage += "You must specify a title!<br/>";
			}
			if (document.addBlog.txtSubTitle.value=="") {
				themessage += "You must specify a subtitle!<br/>";
			}
			if (document.addBlog.theRealURL.value=="") {
				themessage += "You must specify a URL!<br/>";
			}
			if (document.addBlog.postMenu[2].checked == true) {
				if (document.addBlog.txtDateTime.value==" < click to pick a date") {
					themessage += "You must specify a post date!<br/>";
				}
			}
			if (document.addBlog.txtExcerpt.value=="") {
				themessage += "You must specify a post excerpt!<br/>";
			}			
			if (document.addBlog.txtBody.value=="") {
				themessage += "You must specify some post content!";
			}			
			if (themessage == "") {
				document.addBlog.submit();
			} else {
				$("#formErrors").html('<div id="formErrors"><div class="err"><div class="bubble"><h3>Error!</h3>'+themessage+'</div></div></div>');
				scrollTo('main');
				return false;
			}
		}
		function selectVerify() {
			var themessage = "";

			if (document.modBlog.whichBlog.value=="x") {
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
