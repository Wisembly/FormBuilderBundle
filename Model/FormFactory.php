<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

class FormFactory
{
    private $fieldClass;

    public function __construct($fieldClass)
    {
        $this->fieldClass = $fieldClass;
    }

    public function bindDataToForm(FormInterface $form, array $fields)
    {
        foreach ($fields as $fieldArr) {
            $field = new $this->fieldClass();
            $field->setForm($form);

            $this->bindDataToField($field, $fieldArr);
            $form->addField($field);
        }
    }

    public function bindDataToField(FormFieldInterface $field, array $data)
    {
        $type = $data['type'];
        unset($data['type']);

        $field->setType($type);
        $field->setOptions($data);
    }
}
