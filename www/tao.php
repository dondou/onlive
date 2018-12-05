<?php
$curl_loops = 0;//避免死了循环必备

$curl_max_loops = 3;


function curl_get_file_contents($url, $referer='') {

global $curl_loops, $curl_max_loops;

$useragent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";

if ($curl_loops++ >= $curl_max_loops) {

  $curl_loops = 0;

  return false;

}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);

curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_REFERER, $referer);

$data = curl_exec($ch);

$ret = $data;

list($header, $data) = explode("\r\n\r\n", $data, 2);

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

curl_close($ch);

if ($http_code == 301 || $http_code == 302) {

  $matches = array();

  preg_match('/Location:(.*?)\n/', $header, $matches);

  $url = @parse_url(trim(array_pop($matches)));

  if (!$url) {
$curl_loops = 0;

return $data;

  }

  $new_url = $url['scheme'] . '://' . $url['host'] . $url['path']

   . (isset($url['query']) ? '?' . $url['query'] : '');

  $new_url = stripslashes($new_url);

  return curl_get_file_contents($new_url, $last_url);

} else {

  $curl_loops = 0;

  list($header, $data) = explode("\r\n\r\n", $ret, 2);

  return $data;

}

}
echo curl_get_file_contents("https://m.tb.cn/h.38AUp76", $referer='');