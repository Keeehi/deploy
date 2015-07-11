<?php

namespace Deploy;


class Git {
    /** @var Cmd */
    private $cmd;

    function __construct(Cmd $cmd) {
        $this->cmd = $cmd;
    }

    public function isCloneable($url) {
        return $this->cmd->shell('git ls-remote ' . $url);
    }



}