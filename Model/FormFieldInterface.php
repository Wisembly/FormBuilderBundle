<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

interface FormFieldInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form);

    /**
     * @param FormFieldAnswerInterface $fieldAnswer
     */
    public function addFieldAnswer(FormFieldAnswerInterface $fieldAnswer);

    /**
     * toArray
     *
     * @param FormFieldInterface $field
     */
    public static function toArray(FormFieldInterface $field);

    /**
     * fromArray
     *
     * @param FormFieldInterface $field
     * @param array $data
     */
    public static function fromArray(array $data, FormFieldInterface $field = null);
}
