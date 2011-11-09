<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormAnswerInterface
{
    public function getForm();

    public function setForm(FormInterface $form);
}
