<?php

namespace Deploy;


use Milo\Github\Storages\ICache;
use Nette\Caching\Cache;

class GithubCache implements ICache {
    /** @var  Cache */
    private $cache;

    /**
     * @param Cache $cache
     */
    function __construct(Cache $cache) {
        $this->cache = $cache;
    }

    /**
     * @param  string
     * @param  mixed
     * @return mixed  stored value
     */
    function save($key, $value) {
        $this->cache->save($key, $value);
        return $value;
    }

    /**
     * @param  string
     * @return mixed|NULL
     */
    function load($key) {
        return $this->cache->load($key);
    }
}