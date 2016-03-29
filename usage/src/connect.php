<?php

require '/vendor/predis/predis/src/Autoloader.php';

Predis\Autoloader::register();

define("REDIS_PORT", getenv("REDIS_PORT"));
define("BEFORE_DIR", "/opt/yo/sample/before");
define("AFTER_DIR", "/opt/yo/sample/after");

class FileEntries extends Predis\Client {
    private $i;
    private $files;

    function __construct($addr) {
       $this->i = 0;
       $this->files = [];
       parent::__construct($addr);
    }

    function crawl($dir) {
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach($objects as $name => $object){
            if ($object->getFilename() != '.' && $object->getFilename() != '..') {
		$this->files[$this->i] = $name;
	        $this->i++;
            }
        }
    }

    function output() {
        print_r($this->files);
    }
}

/*
print_r($redis->set("hello_world", "Hi from php!"));
$value = $redis->get("hello_world");
var_dump($value);
echo ($redis->exists("Santa Claus")) ? "true" : "false";
*/

$fe = new FileEntries(REDIS_PORT);
$fe->crawl(BEFORE_DIR);
$fe->crawl(AFTER_DIR);
$fe->output();
