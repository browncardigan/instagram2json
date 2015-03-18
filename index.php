<?php

define("INSTAGRAM_USER_NAME", "jonny_yesyes");

$instagram_url = "https://instagram.com/" . INSTAGRAM_USER_NAME . "/";

// get the raw content
$www = curlContents($instagram_url);
$www = end(explode("window._sharedData = ", $www));
$www = current(explode(';</script>', $www));
$www_json = json_decode($www, true);

// re-format
$data = array();
if (isset($www_json['entry_data']['UserProfile'][0]['userMedia'])) {
	foreach ($www_json['entry_data']['UserProfile'][0]['userMedia'] as $img) {
		$data[] = array(
			'url'		=> $img['link'],
			'image' 	=> $img['images']['standard_resolution']['url'],
			'caption'	=> isset($img['caption']['text']) ? $img['caption']['text'] : '',
			'date'		=> $img['created_time']
		);
	}
}

// print
echo json_encode($data);
exit;

function curlContents($url=false, $data=array()) {
	$contents = '';
	if ($url) {
		$ch = curl_init();
		$timeout = 0; // set to zero for no timeout
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		if (count($data) > 0) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$contents = curl_exec($ch);
		curl_close($ch);
	}
	return $contents;
}

?>