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
use Nette\Application\UI\Form;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Tracy\Debugger;
use Tracy\OutputDebugger;


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

    public function postForm_onSubmit(Form $form) {
        $this->redrawControl('repositories');
    }

    protected function createComponentPostForm()
    {
        $form = new Form();
        $form->addText('search')
             ->setAttribute('placeholder', 'eg. Deploy')->setAttribute('class','form-control')->setAttribute('oninput','alarm.setup(this);'); //onpropertychange for <=IE8
        $form->addSubmit('btn', 'Search');

        $form->getElementPrototype()->class('panel-heading ajax');

        $form->onSubmit[] = array($this, 'postForm_onSubmit');
        return $form;
    }

    public function renderDefault($search = null)	{
        $cmd = new Cmd();
        $github = new Github($cmd);
        $configuration = $this->configuration->getConfiguration();

        if(isset($configuration["github-access-tokens"])) {
            $accessTokens = $configuration["github-access-tokens"];
        } else {
            $accessTokens = [];
        }



        $username = "Keeehi";//$github->getUsername();
        $accessToken = $accessTokens[$username];



        $client = new CachedClient(new GithubCache(new Cache($this->storage, 'GitHub')));

        $api = new Api($client);

        if (isset($accessTokens[$username])) {
            $api->setToken(new Token($accessToken));
        }
    //        $response = $api->get('/user');
    //        Debugger::barDump($response);
    //
    //        $result = $api->decode($response);
    //        Debugger::barDump($result);


        // After some date, drop accept part
        //$response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated']);

        if (!empty($search)) {
            $response = $api->get('/search/repositories', ['q'=>$search, 'per_page'=>15, 'sort'=>'updated'], ['accept' => 'application/vnd.github.moondragon+json']);
            $this->template->repositories = $api->decode($response)->items;
        } else {
            $response = $api->get('/user/repos', ['per_page'=>15, 'sort'=>'updated'], ['accept' => 'application/vnd.github.moondragon+json']);
            $this->template->repositories = $api->decode($response);
        }

        Debugger::barDump($response);
        Debugger::barDump($response->getHeader('x-ratelimit-remaining') . " (Reset at " . Nette\Utils\DateTime::from($response->getHeader('x-ratelimit-reset')) . (new Nette\Utils\DateTime())->diff(Nette\Utils\DateTime::from($response->getHeader('x-ratelimit-reset')))->format(' in %h:%I:%S)'));

	}
}
