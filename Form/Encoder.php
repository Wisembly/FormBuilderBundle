<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

class Encoder
{
    public function encode(FormInterface $form)
    {
        $encoded = array();

        foreach ($form->getFields() as $field) {
            $encoded[] = $this->encodeField($field);
        }

        return $encoded;
    }

    public function encodeField(FormFieldInterface $field)
    {
        return array('type' => $field->getType()) + $field->getOptions();
    }
}
