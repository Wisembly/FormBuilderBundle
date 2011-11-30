<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FieldType;
use Symfony\Component\Form\FormFactory;

class Builder
{
    protected $formFactory;
    protected $decoder;
    protected $tallhat;
    protected $fieldConfig;

    public function __construct(FormFactory $formFactory, Decoder $decoder, TallHat $tallhat, $fieldConfig)
    {
        $this->formFactory = $formFactory;
        $this->decoder = $decoder;
        $this->tallhat = $tallhat;
        $this->fieldConfig = $fieldConfig;
    }

    public function build($formid, $bindData = false)
    {
        return $this->buildFields($this->tallhat->findFields($formid), $bindData);
    }

    public function buildFields(array $fields, $bindData = false)
    {
        $builder = $this->formFactory->createBuilder('form', $bindData ? $fields : array());

        foreach ($fields as $i => $field) {
            if ($field instanceof FormFieldInterface) {
                $builder->add((string)$field->getId(), $field->getType(), $field->getOptions());
            } else {
                $field = $this->decoder->decodeField($field);
                $builder->add((string)$i, $field->getType(), $field->getOptions());
            }
        }

        return $builder->getForm();
    }

    /**
     * Lot's of magic here - keep in mind that a form types are the skeleton
     */
    public function buildType($name, array $data = array())
    {
        $data = $this->getTypeOptions($name, $data);
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
                    if (empty($val)) {
                        $builder->add($key, new CollectionType(), array(
                            'required'      => false,
                            'allow_add'     => true,
                            'allow_delete'  => true,
                        ));
                    } else {
                        $builder->add($key, new ChoiceType(), array(
                            'choices' => $configOptions[$key],
                        ));
                    }
                    break;
                default:
                    throw new \InvalidArgumentException('unsupported type '. gettype($val));
            }
        }

        return $builder->getForm();
    }

    public function getTypeOptions($name, array $data)
    {
        if (!$this->formFactory->hasType($name)) {
            throw new \InvalidArgumentException("Field of type '$name' do not exists");
        }

        $type = $this->formFactory->getType($name);
        $defaultOptions = $type->getDefaultOptions(array());
        $configOptions = isset($this->fieldConfig[$name]) ? $this->fieldConfig[$name] : $defaultOptions;

        foreach ($configOptions as $option => $val) {
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

            if (!isset($data[$option])) {
                $data[$option] = $val;
            }

            // fix boolean casting
            if (is_bool($val) && !is_bool($data[$option])) {
                $data[$option] = (bool) $data[$option];
            }
        }

        if (isset($data['choices']) && false === array_search('', $data['choices'])) {
            $data['choices'] += array_fill(count($data['choices']), count($data['choices']) + 2, '');
        }

        return $data;
    }
}
