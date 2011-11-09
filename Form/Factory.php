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

    public function formInstance(array $fields, FormInterface $form = null)
    {
        $form = null === $form ? new $this->formClass : $form;

        // fix http://www.doctrine-project.org/jira/browse/DDC-956
        // we call ->count() to initialize the collection
        $form->getFields()->count();

        $form->resetFields();

        foreach ($fields as $fieldArr) {
            $field = $this->fieldInstance($fieldArr);
            $form->addField($field);
        }

        return $form;
    }
}
