<?php

namespace Deploy;



use Tracy\Debugger;

class Storage {
    private $directory;

    public function __construct($dir) {
        if (!is_dir($dir)) {
            throw new \Exception();
        }
        $this->directory = realpath($dir . "/storage");
    }

    public function storeDeployScript($server, $author, $repository, $script) {
        $path = $this->directory . "/repositories/$server/$author";
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        file_put_contents("nette.safe://$path/$repository", $script);
    }

    public function getDeployScript($server, $author, $repository) {
        $path = $this->directory . "/repositories/$server/$author/$repository";
        if (!is_file($path)) {
            throw new \Exception();
        }

        return file_get_contents("nette.safe://$path");
    }
}