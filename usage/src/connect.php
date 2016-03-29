<?php

require '/vendor/predis/predis/src/Autoloader.php';

Predis\Autoloader::register();

try {
    $redis_addr = getenv("REDIS_PORT");
    $redis = new Predis\Client($redis_addr);
    echo "Successfully connected to Redis";
} catch (Exception $e) {
    echo "Couldn't connected to Redis";
    echo $e->getMessage();
}

$redis->set("hello_world", "Hi from php!");
$value = $redis->get("hello_world");
var_dump($value);

echo ($redis->exists("Santa Claus")) ? "true" : "false";

