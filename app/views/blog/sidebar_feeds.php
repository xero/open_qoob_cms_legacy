		<div class="box feeds">
			<div class="title"><strong>feeds</strong></div>
				<div class="rss">
					<ul>
<?
	if($showNewest == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/rss/blog/newest">newest posts</a></li>
<?
	}
	if($showCat == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/rss/blog/category/<?=$cat; ?>">posts from this category</a></li>
<?
	}
	if($showTag == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/rss/blog/tag/<?=$tag; ?>">posts with this tag</a></li>
<?
	}
	if($showComments == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/rss/blog/comments/<?=$post; ?>">comments from this posts</a></li>
<?
	}
?>
					</ul>
				</div>
				<div class="atom">
					<ul>
<?
	if($showNewest == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/atom/blog/newest">newest posts</a></li>
<?
	}
	if($showCat == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/atom/blog/category/<?=$cat; ?>">posts from this category</a></li>
<?
	}
	if($showTag == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/atom/blog/tag/<?=$tag; ?>">posts with this tag</a></li>
<?
	}
	if($showComments == true) {
?>
						<li><a href="<?=QOOB_DOMAIN; ?>feeds/atom/blog/comments/<?=$post; ?>">comments from this posts</a></li>
<?
	}
?>
					</ul>
				</div>
			</div>