<?php

namespace Deploy;


use Tracy\Debugger;

class Github {
    /** @var Cmd */
    private $cmd;

    /** @var string */
    private $username = null;

    function __construct(Cmd $cmd) {
        $this->cmd = $cmd;
    }

    public function getUsername() {
        if ($this->username !== null) {
            return $this->username;
        }

        $before = microtime(true);
        $this->cmd->shell('ssh -T git@github.com', $output);
        Debugger::barDump(microtime(true) - $before);

        if(preg_match('~Hi ([a-zA-Z0-9]+(:?-[a-zA-Z0-9]+)*)!~', $output, $match)) {
            return $this->username = $match[1];
        }
        return null;
    }
}