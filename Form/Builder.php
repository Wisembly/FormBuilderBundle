<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Symfony\Component\Form\FormFactory;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

class Builder
{
    private $formFactory;

    private $fieldConfig;

    public function __construct(FormFactory $formFactory, $fieldConfig)
    {
        $this->formFactory = $formFactory;
        $this->fieldConfig = $fieldConfig;
    }

    public function buildFields(array $fields)
    {
        $builder = $this->formFactory->createBuilder('form');

        foreach ($fields as $i => $field) {
            if ($field instanceof FormFieldInterface) {
                $builder->add((string)$field->getId(), $field->getType(), $field->getOptions());
            } else {
                $type = $field['type'];
                unset($field['type']);
                $builder->add((string)$i, $type, $field);
            }
        }

        return $builder->getForm();
    }

    public function buildType($name, array $data = array())
    {
        if (!$this->formFactory->hasType($name)) {
            throw new \InvalidArgumentException("Field of type '$name' do not exists");
        }

        $type = $this->formFactory->getType($name);
        $defaultOptions = $type->getDefaultOptions(array());
        $configOptions = isset($this->fieldConfig[$name]) ? $this->fieldConfig[$name] : $defaultOptions;

        foreach ($configOptions as $option) {
            // default value
            $val = null;

            // if option exists set the default value
            if (isset($defaultOptions[$option])) {
                $val = $defaultOptions[$option];
            }

            // if value is an array create some fields
            if (is_array($val) && empty($val)) {
                $val = range(0, 4);
            }

            if (!isset($data[$option])) {
                $data[$option] = $val;
            }
        }

        $builder = $this->formFactory->createBuilder('form', $data);

        foreach ($data as $key => $val) {
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
                    $builder->add($key, new \Symfony\Component\Form\Extension\Core\Type\CollectionType(), array(
                        'allow_add'     => true,
                        'allow_delete'  => true,
                    ));
                    break;
                default:
                    throw new \InvalidArgumentException('unsupported type '. gettype($val));
            }
        }

        return $builder->getForm();
    }
}
