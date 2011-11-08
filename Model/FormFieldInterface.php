<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormFieldInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getOptions();

    /**
     * @return string
     */
    public function setOptions(array $options);
}
