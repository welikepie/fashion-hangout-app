<?php

	define('CLOTHING_JSON', 'https://raw.github.com/welikepie/fashion-hangout-app/v1/data/clothing.jsonp');
	define('JSONP_OFFSET_START', 7);
	define('JSONP_OFFSET_END', 2);
	
	// Retrieve and parse the IDs of clothing used in this instance
	$clothes_id = array_map("intval", explode(",", $_GET['c']));
	
	// Retrieve and parse the clothing data,
	// filter only by the clothes provided in the collection here.
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => CLOTHING_JSON,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
	));
	$json = curl_exec($curl);
	curl_close($curl); unset($curl);
	
	$json = substr($json, JSONP_OFFSET_START, strlen($json) - JSONP_OFFSET_START - JSONP_OFFSET_END);
	$clothing = json_decode($json, true);
	
	$collection = array();
	foreach ($clothing as $item) {
		if (in_array($item['id'], $clothes_id)) {
			$collection[] = $item;
		}
	}
	
	unset($clothing);
	unset($json);
	
	$image = false;

?><!DOCTYPE html>
<html itemscope itemtype="http://schema.org/ImageObject">
	<head>
		<title itemprop="name">Fashion Share</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta itemprop="representativeOfPage" content="true">
		<meta itemprop="description" content="Short blurb for the share page.">
		
		<link rel="stylesheet" href="styles.css" type="text/css">
		<link rel="canonical" href="http://dev.welikepie.com/fashion-share/?<?php echo($_SERVER['QUERY_STRING']); ?>" itemprop="url">
	</head>
	<body>
		<header>
			<h1>Fashion Share</h1>
			<h2>Subtitle</h2>
		</header>
		<section id="collection">
		
			<ul class="items">
				<?php foreach ($collection as &$item) { ?>
				<li>
					<img src="<?php echo(htmlspecialchars($item['photo'])); ?>" alt="<?php echo(htmlspecialchars($item['name'])); ?>"<?php if (!$image) { echo('  itemprop="contentURL"'); $image = true; } ?>>
					<h2><?php echo(htmlspecialchars($item['name'])); ?></h2>
				</li>
				<?php } ?>
			</ul>
		
		</section>
		<footer>
			<div class="subscribe">
				<button type="button">Subscribe to receive updates about your collection</button>
			</div>
			<p>Example text in footer.</p>
		</footer>
	</body>
</html>