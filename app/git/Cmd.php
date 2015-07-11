<?php
/**
 * Created by PhpStorm.
 * User: honzacejhon
 * Date: 09/04/15
 * Time: 11:51 PM
 */

namespace Deploy;


class Cmd {
    public function shell($command, &$output = false, &$returnCode = false) {
        exec("{\n" .$command . "\n} 2>&1", $lines, $result);

        if($output !== false) {
            $output = implode("\n", $lines);
        }

        if($output !== false) {
            $returnCode = $result;
        }

        return $result === 0 ? true : false;
    }
}