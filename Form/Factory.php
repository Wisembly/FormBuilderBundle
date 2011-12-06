<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;

/**
 * Factory
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Factory
{
    private $formClass;
    private $fieldClass;

    public function __construct($formClass, $fieldClass)
    {
        $this->formClass = $formClass;
        $this->fieldClass = $fieldClass;
    }

    public function formInstance()
    {
        return new $this->formClass;
    }

    public function fieldInstance()
    {
        return new $this->fieldClass;
    }
}
