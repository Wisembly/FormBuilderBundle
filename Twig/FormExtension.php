<?php

namespace Balloon\Bundle\FormBuilderBundle\Twig;

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
