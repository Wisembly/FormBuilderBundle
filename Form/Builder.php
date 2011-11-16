<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\Type\FieldType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

class Builder
{
    private $formFactory;
    private $decoder;
    private $tallhat;
    private $fieldConfig;

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
                    $builder->add($key, new CollectionType(), array(
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
