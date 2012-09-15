<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head profile="http://gmpg.org/xfn/11"> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta http-equiv="author" content="xero harrison" /> 
	<meta name="copyright" content="(CC) MMXII xero harrison" /> 
	<title> ://open.qoob.nu/<?=strtolower($title); ?></title> 
	<meta name="generator" content="open qoob v<?=QOOB_VERSION; ?>" /> 
	<meta name="description" content="default open qoob template" /> 
	<meta name="keywords" content="open qoob, qoob" /> 
<?php
	if(isset($meta)) {
?>
	<?=$meta; ?>
<?		
	}
?>	
	<link rel="stylesheet" type="text/css" id="qoob-css" href="<?=BASE_URL; ?>style/css/pixelgraff.css" media="all"/>
	<script type="text/javascript" src="<?=QOOB_DOMAIN; ?>qoob_stats/detect.js"></script> 
	<script type="text/javascript" src="<?=BASE_URL; ?>style/js/jquery-1.7.1.min.js" charset="utf-8"></script> 
<?php
	if(isset($jsfiles)) {
?>
	<?=$jsfiles; ?>
<?		
	}
?>
	<script type="text/javascript">
		/* <![CDATA[ */
<?=$script; ?>
		/* ]]> */
	</script>
</head>	
<body> 
<div id="main">
	<div id="spacer"> &nbsp; </div>
	<div id="leftCol">
		<?=$body; ?>
	</div>
	<!-- sidebar -->
	<div id="sidebar">
		<?=$sidebar; ?>
	</div>
	<!-- end sidebar -->	
</div>
<p><br class="clear"/></p>
<!-- stuck header -->
<div id="top">
	<div id="graff">
		<div class="floatLeft"><h1>open qoob</h1></div>
		<div id="menu">
<?php
	$as = '';
	$gs = '';
	$cs = '';
	$bs = '';
	$es = '';

	switch ($selected) {
		case 'about':
			$as = 'class="selected" ';
		break;
		case 'gallery':
			$gs = 'class="selected" ';
		break;
		case 'code':
			$cs = 'class="selected" ';
		break;
		case 'blog':
			$bs = 'class="selected" ';
		break;
		case 'contact':
			$es = 'class="selected" ';
		break;
	}
?>			
			<a <?=$as; ?>href="<?=QOOB_DOMAIN; ?>">about</a>
			<a <?=$gs; ?>href="<?=QOOB_DOMAIN; ?>gallery/">gallery</a>
			<a <?=$cs; ?>href="<?=QOOB_DOMAIN; ?>code/">code</a>
			<a <?=$bs; ?>href="<?=QOOB_DOMAIN; ?>blog/">blog</a>
			<a <?=$es; ?>href="<?=QOOB_DOMAIN; ?>contact/">contact</a>
			
			<form method="post" id="searchform" action="<?=QOOB_DOMAIN; ?>search/">
				<div id="find">
					<div class="text"><input type="text" id="search" name="search" value="find something..." onfocus="if (this.value == 'find something...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'find something...';}" tabindex="1" /></div>
					<div class="button"><input type="submit" value="search" /></div>
				</div>
			</form>
			<div id="icons">
				<a href="<?=QOOB_DOMAIN; ?>feeds/rss/" id="rss">&nbsp;</a>
				<a href="<?=QOOB_DOMAIN; ?>feeds/atom/" id="atom">&nbsp;</a>
			</div>
		</div>
		<div id="canonicals">
			<div id="back"><?php if(isset($canonicalBack)) { ?><?=$canonicalBack; ?><? } ?></div>
			<div id="now"><?php if(isset($canonicalNow)) { ?><?=$canonicalNow; ?><? } ?></div>
			<div id="next"><?php if(isset($canonicalNext)) { ?><?=$canonicalNext; ?><? } ?></div>
		</div>
	</div>
</div>
<!-- end stuck header -->
<? 
		//roman numerals
		$N = date('Y');
        $c='IVXLCDM'; 
        for($a=5,$b=$s='';$N;$b++,$a^=7) 
                for($o=$N%$a,$N=$N/$a^0;$o--;$s=$c[$o>2?$b+$N-($N&=-2)+$o=1:$b].$s);  
?>
<!-- footer -->
<div id="footer">
	<div id="area">
		<div id="logo">
			<a href="http://creativecommons.org/licenses/by-sa/3.0/" title="Creative Commons — Attribution-ShareAlike 3.0 Unported — CC BY-SA 3.0"><img src="<?=BASE_URL; ?>style/img/cc.png" alt="Creative Commons — Attribution-ShareAlike 3.0 Unported — CC BY-SA 3.0" title="Creative Commons — Attribution-ShareAlike 3.0 Unported — CC BY-SA 3.0" class="noBorder"/></a>
		</div>
	    <div id="text">
	    	<i><?=$s; ?></i> <a href="<?=QOOB_DOMAIN; ?>"><?=QOOB_DOMAIN; ?></a>
	    </div>
		<div id="git">
			<a href="http://open.qoob.nu/" title="open qoob">&nbsp;</a>
		</div>
	</div>
</div>		
<!-- end footer -->			
</body>
</html>