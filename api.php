<?php

function ambilHTML($url){

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

$html = curl_exec($ch);

curl_close($ch);

return $html;
}

$date = date("Y/m/d");

$urlRenungan = "https://alkitab.mobi/2/renungan/roc/$date/";
$urlQuote = "https://rehobot.org/category/quote/";

$htmlRenungan = ambilHTML($urlRenungan);
$htmlQuote = ambilHTML($urlQuote);

$renungan = "Renungan tidak ditemukan";
$quote = "Quote tidak ditemukan";

/* PARSE RENUNGAN */

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dom->loadHTML($htmlRenungan);

$articles = $dom->getElementsByTagName("article");

if($articles->length > 0){
$renungan = $dom->saveHTML($articles->item(0));
}

/* PARSE QUOTES */

$dom2 = new DOMDocument();
$dom2->loadHTML($htmlQuote);

$blocks = $dom2->getElementsByTagName("blockquote");

if($blocks->length > 0){

$quotes = [];

foreach($blocks as $b){
$quotes[] = $dom2->saveHTML($b);
}

$index = date("z") % count($quotes);

$quote = $quotes[$index];
}

header("Content-Type: application/json");

echo json_encode([
"renungan"=>$renungan,
"quote"=>$quote
]);
