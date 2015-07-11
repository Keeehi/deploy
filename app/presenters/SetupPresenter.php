<?php

namespace App\Presenters;

use Deploy\Cmd;
use Deploy\Git;
use Nette,
	App\Model;


/**
 * Setup presenter.
 */
class SetupPresenter extends BasePresenter
{

	public function renderDefault()
	{
        $git = new Git(new Cmd());
        echo $git->getUsername();
	}

}
