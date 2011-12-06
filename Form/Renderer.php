<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Twig_Environment;

/**
 * This class can be used if you want to render frontend/backend templates for a field
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Renderer
{
    private $builder;
    private $formFactory;
    private $formExtension;

    public function __construct(Builder $builder, FormFactory $formFactory, FormExtension $formExtension, Twig_Environment $twig)
    {
        $this->builder = $builder;
        $this->formFactory = $formFactory;
        $this->formExtension = $formExtension;

        $this->formExtension->initRuntime($twig);
    }

    public function renderFront($type, array $options = array())
    {
        $formType = $this->formFactory->getType($type);
        $defaultOptions = $formType->getDefaultOptions(array());

        $formBuilder = $formType
            ->createBuilder('form', $this->formFactory, $defaultOptions)
            ->setTypes(array($formType));

        $formView = new FormView();

        $formType->buildForm($formBuilder, $defaultOptions);
        $formType->buildView($formView, $formBuilder->getForm());

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

