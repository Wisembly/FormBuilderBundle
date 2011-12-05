<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormAnswerInterface
{
    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form);

    /**
     * @return ArrayCollection
     */
    public function getFieldAnswers();

    /**
     * @param FormFieldInterface $field
     */
    public function addFieldAnswer(FormFieldAnswerInterface $fieldAnswer);
}
