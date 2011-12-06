<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Model;

/**
 * FormFieldInterface
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
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
