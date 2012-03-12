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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FieldType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Builder
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Builder
{
    protected $formFactory;
    protected $promise;
    protected $fieldConfig;

    public function __construct(FormFactory $formFactory, Promise $promise, $fieldConfig)
    {
        $this->formFactory = $formFactory;
        $this->promise = $promise;
        $this->fieldConfig = $fieldConfig;
    }

    public function build($formid, $bindData = false)
    {
        return $this->buildFields($this->promise->findFields($formid), $bindData);
    }

    public function buildFields(array $fields, $bindData = false)
    {
        $builder = $this->formFactory->createBuilder('form', $bindData ? $fields : array());

        foreach ($fields as $i => $field) {
            $builder->add((string)$i, $field->getType(), $field->getOptions());
        }

        return $builder->getForm();
    }

    /**
     * get a FormType
     *
     * @param mixed $type
     */
    public function getType($type)
    {
        if (!$this->formFactory->hasType($type)) {
            throw new \InvalidArgumentException("type '$type' do not exists");
        }

        return $this->formFactory->getType($type);
    }

    /**
     * Build the Form of a given FormType
     *
     * @param FormTypeInterface $type
     * @param array $data
     */
    public function buildType(FormTypeInterface $type, array $values = array())
    {
        $data = $this->getTypeOptions($type, $values);
        $configOptions = $type->getDefaultOptions(array());
        $configOptions = isset($this->fieldConfig[$type->getName()]) ? $this->fieldConfig[$type->getName()] : $configOptions;
        $builder = $this->formFactory->createBuilder('form', $data);

        foreach ($data as $key => $val) {
            $val = $configOptions[$key];
            switch(gettype($val)) {
                case 'boolean':
                    $builder->add($key, new CheckboxType(), array(
                        'required' => false,
                    ));
                    break;
                case 'NULL':
                case 'integer':
                case 'double':
                case 'string':
                    $builder->add($key, new FieldType(), array(
                        'required' => false,
                    ));
                    break;
                case 'array':
                    // if there's no values, it's some blank fields
                    if (empty($val)) {
                        $builder->add($key, new CollectionType(), array(
                            'required'      => false,
                            'allow_add'     => true,
                            'allow_delete'  => true,
                        ));
                    // else a select
                    } else {
                        $builder->add($key, new ChoiceType(), array(
                            'choices' => $configOptions[$key],
                            'expanded' => false
                        ));
                    }
                    break;
                default:
                    throw new \InvalidArgumentException('unsupported type '. gettype($val));
            }
        }

        return $builder->getForm();
    }

    /**
     * For a given FormType we get all options {@see FormTypeInterface::getDefaultOptions()}
     *
     * @param FormTypeInterface $type   The FormType
     * @param array $values             Values for FormType options
     */
    public function getTypeOptions(FormTypeInterface $type, array $values = array(), $tag = null)
    {
        
        if (isset($values['multiple'])) {
            $this->fieldConfig['choice']['expanded'] = true;
        }
        
        $options = isset($this->fieldConfig[$type->getName()])
            ? $this->fieldConfig[$type->getName()]
            : $type->getDefaultOptions(array());


        if ($tag != null) {
            $this->fieldConfig['html']['tag'] = $tag;
        }


        foreach ($options as $option => $val) {

            // if field as an array value
            if (is_array($val)) {
                // if value is an empty array create some fields
                if (empty($val)) {
                    $val = array_fill(0, 4, '');
                }
                // else we set the default value to the first element
                else {
                    $val = reset($val);
                }
            }

            // if a value is provided, bind it
            if (!isset($values[$option])) {
                $values[$option] = $val;
            }

            // fix boolean casting
            if (is_bool($val) && !is_bool($values[$option])) {
                $values[$option] = (bool) $values[$option];
            }
        }

        // if the field name is 'choices' we add some fields
        if (isset($values['choices']) && false === array_search('', $values['choices'])) {
            $values['choices'] += array_fill(count($values['choices']), count($values['choices']) + 2, '');
        }

        return $values;
    }
}
