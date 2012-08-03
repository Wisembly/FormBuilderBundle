<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Entity;

use Balloon\Bundle\FormBuilderBundle\Model\FormFieldAnswerInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Balloon\Bundle\FormBuilderBundle\Entity\FormField
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class FormField implements FormFieldInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var array $options
     *
     * @ORM\Column(name="options", type="array")
     */
    private $options = array();

    /**
     * @var string $form
     *
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="fields")
     */
    private $form;

    /**
     * @ORM\OneToMany(targetEntity="FormFieldAnswer", mappedBy="field", cascade={"remove"})
     */
    private $fieldAnswer;

    public function __construct()
    {
        $this->fieldAnswer = new ArrayCollection();
    }

    public static function toArray(FormFieldInterface $field)
    {
        return array(
            'type'      => $field->getType(),
            'options'   => $field->getOptions(),
        );
    }

    public static function fromArray(array $data, FormFieldInterface $field = null)
    {
        $field = null === $field ? new static() : $field;

        if (isset($data['type'])) {
            $field->setType($data['type']);
        }

        if (isset($data['options'])) {
            // fix boolean casting
            foreach ($data['options'] as $key => $val) {
                if ('false' === $val) $data['options'][$key] = false;
                if ('true' === $val) $data['options'][$key] = true;
            }

            // remove empty choices
            if (isset($data['options']['choices'])) {
                $data['options']['choices'] = array_filter($data['options']['choices'], 'strlen');
            }

            $field->setOptions($data['options']);
        }

        return $field;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return FormField
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set options
     *
     * @param array $options
     * @return FormField
     */
    public function setOptions(array $options)
    {
        if (isset($options['choices'])) {
            $choices = $options['choices'];
            unset($options['choices']);

            foreach ($choices as $key => $val) {
                if (!empty($val)) {
                    $options['choices'][$key] = $val;
                }
            }

        }

        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set form
     *
     * @param string $form
     * @return FormField
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;
        return $this;
    }

    public function addFieldAnswer(FormFieldAnswerInterface $fieldAnswer)
    {
        $fieldAnswer->setField($this);

        $this->fieldAnswer->add($fieldAnswer);
    }
}
