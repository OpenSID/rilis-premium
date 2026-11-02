<?php

/**
 * Router untuk `php artisan serve` (PHP built-in server, tidak membaca .htaccess).
 *
 * Meniru public/.htaccess: hanya berkas statis yang dilayani langsung, folder
 * fisik diteruskan ke front controller, dan PHP selain index.php di root
 * ditolak (premium#7420).
 */

$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && $uri !== '/index.php') {
    if (preg_match('/\.(php[0-9]*|phtml|phtm|pht|phps|phar)(\/|$)/i', $uri)) {
        http_response_code(403);

        return;
    }

    if (is_file($publicPath.$uri)) {
        return false;
    }
}

$formattedDateTime = date('D M j H:i:s Y');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$remoteAddress = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];

file_put_contents('php://stdout', "[$formattedDateTime] $remoteAddress [$requestMethod] URI: $uri\n");

require_once $publicPath.'/index.php';
