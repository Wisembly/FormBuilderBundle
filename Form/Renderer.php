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

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Twig_Environment;

/**
 * Renderer
 *
 * This class can be used if you want to render frontend/backend templates for a field
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Renderer
{
    private $builder;
    private $formFactory;
    private $formExtension;
    private $theme;

    public function __construct(Builder $builder, FormFactory $formFactory, FormExtension $formExtension, Twig_Environment $twig, array $themes = array())
    {
        $this->builder = $builder;
        $this->formFactory = $formFactory;
        $this->formExtension = $formExtension;
        $this->themes = $themes;

        $this->formExtension->initRuntime($twig);
    }

    public function renderFront($type, array $options = array())
    {
        $formView = $this->formFactory
            ->createBuilder($type, null, $options)
            ->getForm()
            ->createView();

        if (!empty($this->themes)) {
            $this->formExtension->setTheme($formView, $this->themes);
        }

        return $this->formExtension->renderRow($formView);
    }

    public function renderBack($type, array $options = array())
    {
        $formType = $this->formFactory->getType($type);
        $typeOptions = $this->builder->getTypeOptions($formType, $options);
        $formType = $this->formFactory->getType($type);
        $formView = $this->builder->buildType($formType, $typeOptions)->createView();

        return $this->formExtension->renderRow($formView);
    }
}

