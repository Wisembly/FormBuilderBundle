<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getFields();

    /**
     * @param FormFieldInterface $field
     */
    public function addField(FormFieldInterface $field);

    /**
     * @param FormAnswerInterface $answer
     */
    public function addAnswer(FormAnswerInterface $answer);
}
