<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Twig;

/**
 * FormExtension
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class FormExtension extends \Twig_Extension
{
    private $fieldConfig;

    public function __construct(array $fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
    }

    public function getFunctions()
    {
        // @codeCoverageIgnoreStart
        return array(
            'form_types' => new \Twig_Function_Method($this, 'getFormTypes'),
        );
        // @codeCoverageIgnoreEnd
    }

    public function getFormTypes()
    {
        return array_keys($this->fieldConfig);
    }

    public function getName()
    {
        return 'balloon_form_builder';
    }
}
