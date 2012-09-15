		(function($) {
			$(document).ready(function(){
				getVisits();
				getBrowsers();
				getPages();
				getReferrers();
				getLocations();
				getSearches();
			});
		})(jQuery);

		function getVisits() {
			var range = $("#visitsRange").val();
			var view = $("#visitsView").val();
			var visitsType = $("#visitsType").val();
			$("#visits").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=visits&range="+range+"&view="+view+"&visitsType="+visitsType,
				cache: false,
				success: function(html){
					$("#visits").html(html);
				}
			});
		}
		function getBrowsers() {
			var datatype = $("#browserData").val();
			var range = $("#browserRange").val();
			var view = $("#browserView").val();
			$("#browsers").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=browsers&datatype="+datatype+"&range="+range+"&view="+view,
				cache: false,
				success: function(html){
					$("#browsers").html(html);
				}
			});
		}
		function getLocations() {
			var range = $("#locationsRange").val();
			var view = $("#locationsView").val();
			$("#locations").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=locations&range="+range+"&view="+view,
				cache: false,
				success: function(html){
					$("#locations").html(html);
				}
			});			
		}
		function getPages() {
			var range = $("#pagesRange").val();
			var limit = $("#pagesLimit").val();
			$("#pages").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=pages&range="+range+"&limit="+limit,
				cache: false,
				success: function(html){
					$("#pages").html(html);
				}
			});			
		}
		function getReferrers() {
			var range = $("#referrersRange").val();
			var limit = $("#referrersLimit").val();
			$("#referrers").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=referrers&range="+range+"&limit="+limit,
				cache: false,
				success: function(html){
					$("#referrers").html(html);
				}
			});			
		}
		function getSearches() {
			var range = $("#searchesRange").val();
			var limit = $("#searchesLimit").val();
			$("#searches").html('loading...');
			$.ajax({
				type: "POST",
				url: "<?=QOOB_DOMAIN; ?>backdoor/ajax/",
				data: "action=stats&type=searches&range="+range+"&limit="+limit,
				cache: false,
				success: function(html){
					$("#searches").html(html);
				}
			});			
		}
