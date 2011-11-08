<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

use Symfony\Component\Form\FormFactory;

class FormBuilder
{
    private $formFactory;

    private $fieldConfig;

    public function __construct(FormFactory $formFactory, $fieldConfig)
    {
        $this->formFactory = $formFactory;
        $this->fieldConfig = $fieldConfig['fields'];
    }

    public function buildFields(array $fields)
    {
        $builder = $this->formFactory->createBuilder('form');

        foreach ($fields as $field) {
            if (!$field instanceof FormFieldInterface) {
                throw new \ErrorException('argument should only contains instances of FormFieldInterface');
            }

            $builder->add($field->getId(), $field->getType(), $field->getOptions());
        }

        return $builder->getForm();
    }

    public function buildType($name)
    {
        if (!$this->formFactory->hasType($name)) {
            throw new \InvalidArgumentException("Field of type '$name' do not exists");
        }

        $type = $this->formFactory->getType($name);

        $defaultOptions = array();
        foreach ($type->getDefaultOptions(array()) as $key => $val) {
            if (!isset($this->fieldConfig[$name]) || false !== array_search($key, $this->fieldConfig[$name])) {
                $defaultOptions[$key] = $val;
            }
        }

        $builder = $this->formFactory->createBuilder('form', $defaultOptions);

        foreach ($defaultOptions as $key => $val) {
            switch(gettype($val)) {
                case 'boolean':
                    $builder->add($key, new \Symfony\Component\Form\Extension\Core\Type\CheckboxType());
                    break;
                case 'NULL':
                case 'integer':
                case 'double':
                case 'string':
                    $builder->add($key, new \Symfony\Component\Form\Extension\Core\Type\FieldType(), array(
                        'required' => false,
                    ));
                    break;
                case 'array':
                    break;
                default:
                    throw new \InvalidArgumentException('unsupported type '. gettype($val));
            }
        }

        return $builder->getForm();
    }
}
