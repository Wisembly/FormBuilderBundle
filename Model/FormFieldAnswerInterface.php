<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormFieldAnswerInterface
{
    /**
     * @param FormAnswerInterface $answer
     */
    public function setAnswer(FormAnswerInterface $answer);

    /**
     * @param FormFieldInterface $field
     */
    public function setField(FormFieldInterface $field);

    /**
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getScalarValue();
}
