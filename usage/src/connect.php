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
                $this->set($this->i, $name);
                $this->set($this->i."_name", $object->getFilename());
                $this->set($this->i."_contents", file_get_contents($name));
	        $this->i++;
            }
        }
    }

    function output() {
        $j = 0;
	while ($this->get($j) !== null) {
            echo $this->get($j)."\n";
            echo $this->get($j."_name")."\n";
            echo $this->get($j."_contents")."\n";
            $j++;
        }
    }
}

$fe = new FileEntries(REDIS_PORT);
$fe->crawl(BEFORE_DIR);
$fe->crawl(AFTER_DIR);
$fe->output();
