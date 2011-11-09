<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

class Factory
{
    private $formClass;
    private $fieldClass;

    public function __construct($formClass, $fieldClass)
    {
        $this->formClass = $formClass;
        $this->fieldClass = $fieldClass;
    }

    public function formInstance()
    {
        return new $this->formClass;
    }

    public function fieldInstance()
    {
        return new $this->fieldClass;
    }
}
