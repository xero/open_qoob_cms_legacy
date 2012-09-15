<div class="post cat<?=' '.$mainCat; ?>">
	<div class="heading">
<?
		if($url != '') {
?>
		<h1 class="title"><a href="<?=QOOB_DOMAIN.$url; ?>"><?=$title; ?></a>
<?
		} else {
?>
		<h1 class="title"><?=$title; ?>
<?			
		}
		if($comments == 0 || $comments == '') {
?>
		</h1>
<?						
		} else {
?>
		&nbsp;<a class="count" href="<?=QOOB_DOMAIN.$url; ?>#comments" title="Leave a comment"><em><?=$comments; ?></em><span> comments</span></a></h1>
<?						
		}
?>
		<h2 class="subtitle"><em>
<?
		if($mainCat != '' && $mainCat != 'coverletter' && $mainCat != 'resume' && $mainCat != 'about' && $mainCat != 'contact') {
?>
			<a href="<?=QOOB_DOMAIN.'blog/'.$mainCat; ?>"><?=$mainCat; ?></a> :: <?=$subtitle; ?>
		</em></h2>
<?						
		} else {
?>
			<?=$subtitle; ?>
		</em></h2>
<?						
		}
?>		
	</div>
	<div class="content">
		<?=$content; ?>
	</div>
</div>