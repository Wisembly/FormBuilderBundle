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
 * FormAnswerInterface
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
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
