<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormFieldAnswerInterface
{
    public function setAnswer(FormAnswerInterface $answer);

    public function getAnswer();

    public function setField(FormFieldInterface $field);

    public function getField();

    public function setValue($value);

    public function getValue();
}
