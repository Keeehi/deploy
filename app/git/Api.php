<?php

namespace Deploy;


use Milo\Github\Http\Request;

class Api extends \Milo\Github\Api {
    public function get($urlPath, array $parameters = [], array $headers = [])
    {

        return $this->request(
            $this->createRequest(Request::GET, $urlPath, $parameters, $headers)
        );

        return parent::get($urlPath, $parameters, $headers); // TODO: Change the autogenerated stub
    }


}