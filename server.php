<?php

$publicPath = __DIR__.'/public';
$publicRealPath = realpath($publicPath);
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$requestedFile = realpath($publicPath.$uri);

if ($uri !== '/' && $publicRealPath && $requestedFile && str_starts_with($requestedFile, $publicRealPath) && is_file($requestedFile)) {
    return false;
}

require_once $publicPath.'/index.php';
