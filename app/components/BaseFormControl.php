<?php
namespace Deploy;

use Nette\Utils\Strings;

class BaseFormControl extends \Nette\Application\UI\Control
{
    public function createTemplate($class = null)
    {
        $template = parent::createTemplate($class);
        
        if ($template instanceof \Nette\Bridges\ApplicationLatte\Template) {
            $path = \Nette\Reflection\ClassType::from($this)->getFileName();

            if (Strings::substring($path, -4) === '.php') {
                $path = Strings::substring($path, 0, -4);
            }
            $path .= ".latte";

            // TODO default template

            $template->setFile($path); // automatické nastavení šablony
        }
        $template->_form = $template->form = $this['form']; // kvůli snippetům
        return $template;
    }

    public function render()
    {
        if ($this->template instanceof \Nette\Bridges\ApplicationLatte\Template
            && !is_file($this->template->getFile())) {

            $args = func_get_args();
            return call_user_func_array(array($this['form'], 'render'), $args);
        } else {
            $this->template->render();
        }
    }
}