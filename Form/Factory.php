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

    public function fieldInstance(array $encoded)
    {
        $type = $encoded['type'];
        unset($encoded['type']);

        $field = new $this->fieldClass;
        $field->setType($type);
        $field->setOptions($encoded);

        return $field;
    }

    public function formInstance(array $fields)
    {
        $form = new $this->formClass;

        foreach ($fields as $fieldArr) {
            $field = $this->fieldInstance($fieldArr);
            $form->addField($field);
        }

        return $form;
    }
}
