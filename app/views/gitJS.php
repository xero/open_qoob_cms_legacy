		SyntaxHighlighter.defaults.toolbar = false;
		SyntaxHighlighter.all();

		function go(type) {
			var url = '<?=$URL; ?>';
			if(type=='branch') {
				url += document.git.branch.value;
			} else {
				url += document.git.branch.value+'/'+document.git.commit.value;
			}
			window.location.href= url;	
		}
