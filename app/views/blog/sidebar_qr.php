<?php
/*
	this is a hack for qoob urls that are too long to be converted into a QR code. 
*/
	$url = RAW_URL;
	if(strlen($url) > 105) {
		$url = BASE_URL;
		$i = count(library::catalog()->url);
		for($i = 0; $i < count(library::catalog()->url); $i++) {
			if((strlen($url)+strlen(library::catalog()->url[$i])) < 105) {
				$url .= library::catalog()->url[$i].'/';
			} else {
				break;
			}
		}
		//clean up url if shortening breaks its functionality
		$test = substr($url, -5);
		if($test == 'tree/' || $test == 'blob/' || $test == 'page/') {
			$url = substr($url, 0, strlen($url)-5);
		}
	}
?>
		<div class="box qr">
			<div class="title"><strong>QR Code</strong></div>
			<p>
				<img src="<?=QOOB_DOMAIN; ?>qr/<?=$url; ?>" alt="QRcode" /><br/><br/>
				<?=$url; ?>
			</p>
		</div>