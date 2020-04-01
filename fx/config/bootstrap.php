<?php
$host = explode('.', $_SERVER["HTTP_HOST"]);
if (count($host) > 2) {
    define('DOMAIN', $host[1] . '.' . $host[2]);
} else {
    define('DOMAIN', $host[0] . '.' . $host[1]);
}
//$_SERVER['HTTPS']='on';