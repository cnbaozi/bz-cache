<?php
/**
 * Created by PhpStorm.
 * User: Baozi
 * Date: 2014/11/16
 * Time: 15:15
 */

// site config start
$dirs = array(
    'test1' => 'http://test.com/'
);
// site config end

// 也是为了安全考虑
$noCacheType = array('php', 'php5', 'php4');
$cache = true;

define('ROOT_DIR', dirname(__FILE__));
$uri = strtok($_SERVER['REQUEST_URI'], '?');

if (strpos($uri, '..') !== false) {
    die('path cannot include ".."');
}
if (strpos($uri, '/get.php') === 0) {
    die("access error.");
}

// white list
$uriInfo = explode('/', $uri);
$dir = $uriInfo[1];
if (!isset($dirs[$dir])) {
    die($dir . " not in white list.");
}

$targetUri = substr($uri, strlen($dir) + 2);
$url = $dirs[$dir] . $targetUri;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$file = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode!=200) {
    die("http error: $httpCode");
}
// file type block list
$_uriArr = explode('.', $uri);
if (in_array(array_pop($_uriArr), $noCacheType)) {
    $cache = false;
}
if ($cache) {
    $path = ROOT_DIR . $uri;
    $parentDir = dirname($path);
    if (!is_dir($parentDir)) {
        mkdir($parentDir, 0777, true);
    }
    file_put_contents($path, $file);
}

header('Content-Type: '.$contentType);
echo $file;
