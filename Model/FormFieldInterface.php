<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormFieldInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getFields();

    public function addField(FieldInterface $field);
}
