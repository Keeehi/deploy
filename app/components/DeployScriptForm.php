<?php
namespace Deploy;

use \Nette\Application\UI\Form;

class DeployScriptForm extends BaseFormControl {
    public $onSuccess;
    public $values;


    protected function createComponentForm() {
        $form = new Form();

        $form->addTextArea('script', null, null, 15);

        $form->addSubmit('submit');


        $form->onSuccess[] = $this->processForm;

        return $form;
    }

    public function processForm(Form $form) {
        $this->onSuccess($form->getValues());
    }
}

interface IDeployScriptFormFactory {
    /** @return DeployScriptForm */
    function create();
}