<?php

namespace Deploy;


use Milo\Github\Http\CachedClient;
use Milo\Github\OAuth\Token;
use Nette\Application\UI\Control;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tracy\Debugger;

class GitHubComponent extends Control{
    private $githubAccessTokens;

    /** @var IStorage */
    private $storage;

    function __construct($githubAccessTokens = [], IStorage $storage)
    {
        $this->githubAccessTokens = $githubAccessTokens;
        $this->storage = $storage;
    }

    public function handleClick() {
        //file_put_contents(__DIR__."/tu.txt", 123);
    }

    public function render() {
        $template = $this->template;
        $template->setFile(__DIR__ . '/GitHubComponent.latte');

        $cmd = new Cmd();
        $github = new Github($cmd);


        $username = $github->getUsername();
        $accessToken = $this->githubAccessTokens[$username];


        $client = new CachedClient(new GithubCache(new Cache($this->storage, 'GitHub')));

        $api = new Api($client);

        $api->setToken(new Token($accessToken));
        //        $response = $api->get('/user');
        //        Debugger::barDump($response);
        //
        //        $result = $api->decode($response);
        //        Debugger::barDump($result);


        // After some date, drop accept part
        //$response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated']);
        $response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated'], ['accept' => 'application/vnd.github.moondragon+json']);

        $repositories = $api->decode($response);
        if(!$this->presenter->isAjax()) {
            $repositories = array();
        }
        $template->repositories = $repositories;

        // a vykreslíme ji
        $template->render();
    }
}