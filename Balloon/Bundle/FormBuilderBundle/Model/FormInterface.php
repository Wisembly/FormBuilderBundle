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
 * FormInterface
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
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
