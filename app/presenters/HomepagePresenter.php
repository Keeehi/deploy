<?php

namespace App\Presenters;

use Deploy\Cmd;
use Deploy\Config;
use Deploy\Git;
use Deploy\Github;
use Deploy\GithubCache;
use Milo\Github\Api;
use Milo\Github\Http\CachedClient;
use Milo\Github\OAuth\Token;
use Nette,
	App\Model;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tracy\Debugger;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
    /** @var Config */
    private $configuration;

    /** @var IStorage */
    private $storage;

    function __construct(Config $config, IStorage $storage) {
        $this->configuration = $config;
        $this->storage = $storage;
    }

    public function renderDefault()
	{
        $cmd = new Cmd();
        $github = new Github($cmd);
        $configuration = $this->configuration->getConfiguration();

        if(isset($configuration["github-access-tokens"])) {
            $accessTokens = $configuration["github-access-tokens"];
        } else {
            $accessTokens = [];
        }



        $username = $github->getUsername();
        $accessToken = $accessTokens[$username];



        $client = new CachedClient(new GithubCache(new Cache($this->storage, 'GitHub')));

        $api = new Api($client);

        if (isset($accessTokens[$github->getUsername()])) {
            $api->setToken(new Token($accessToken));
        }
    //        $response = $api->get('/user');
    //        Debugger::barDump($response);
    //
    //        $result = $api->decode($response);
    //        Debugger::barDump($result);


        // After some date, drop accept part
        //$response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated']);
        $response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated'], ['accept' => 'application/vnd.github.moondragon+json']);
        $this->template->repositories = $api->decode($response);
	}
}
