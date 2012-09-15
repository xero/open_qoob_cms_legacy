		<div class="box">
			<div class="title"><strong>meta</strong></div>
			<?=$title; ?> was posted on <?=$date; ?> 
			<?
				if(substr_count($cats, ',') > 0) {
			?>
			in the categories: <?=$cats; ?>
			<?					
				} else {
			?>
			in the category: <?=$cats; ?>
			<?	
				}
			?>
			and tagged: <?=$tags; ?>.<br/><br/>
			<?
				if($comments == 0) {
			?>
			<a href="#comments">comments</a>&nbsp;are currently disabled.
			<?					
				}  else {
			?>
			you can leave a <a href="#comment">comment</a>.
			<?										
				}
			?>
		</div>