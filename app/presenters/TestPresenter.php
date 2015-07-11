<?php

namespace App\Presenters;

use Deploy\Cmd;
use Deploy\Config;
use Deploy\Git;
use Deploy\Github;
use Deploy\GithubCache;
use Deploy\GitHubComponent;
use Milo\Github\Api;
use Milo\Github\Http\CachedClient;
use Milo\Github\OAuth\Token;
use Nette,
	App\Model;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tracy\Debugger;


/**
 * Test presenter.
 */
class TestPresenter extends BasePresenter
{
    /** @var Config */
    private $configuration;

    /** @var IStorage */
    private $storage;

    function __construct(Config $config, IStorage $storage) {
        $this->configuration = $config;
        $this->storage = $storage;
    }

    /**
     * @return GitHubComponent
     */
    protected function createComponentGhc()
    {
        $configuration = $this->configuration->getConfiguration();

        if(isset($configuration["github-access-tokens"])) {
            $accessTokens = $configuration["github-access-tokens"];
        } else {
            $accessTokens = [];
        }

        $fifteen = new GitHubComponent($accessTokens, $this->storage);
        $fifteen->redrawControl();
        return $fifteen;
    }

    public function renderDefault()
	{
	}
}
