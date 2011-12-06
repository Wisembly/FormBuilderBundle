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
 * FormFieldAnswerInterface
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
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
