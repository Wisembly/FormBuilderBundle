<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getFields();
}
