<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;

class Decoder
{
    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function decodeField(array $encoded)
    {
        $type = $encoded['type'];
        unset($encoded['type']);

        $field = $this->factory->fieldInstance();
        $field->setType($type);
        $field->setOptions($encoded);

        return $field;
    }

    public function decode(FormInterface $form, array $fields)
    {
        // fix http://www.doctrine-project.org/jira/browse/DDC-956
        $form->getFields()->count();

        $form->resetFields();

        foreach ($fields as $fieldArr) {
            $field = $this->decodeField($fieldArr);
            $form->addField($field);
        }
    }
}
