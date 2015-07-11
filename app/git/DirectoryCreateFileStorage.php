<?php

namespace Deploy;


use Milo\Github\Storages\ICache;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Caching\Storages\IJournal;

class DirectoryCreateFileStorage extends  FileStorage {
    public function __construct($dir, IJournal $journal = NULL) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        parent::__construct($dir, $journal);
    }
}