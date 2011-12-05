<?php

namespace Balloon\Bundle\FormBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldAnswerInterface;

/**
 * Balloon\Bundle\FormBuilderBundle\Entity\FormField
 *
 * @ORM\Table()
 * @ORM\Entity
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
        $field = null === $field ? new self() : $field;

        if (isset($data['type']))       $field->setType($data['type']);
        if (isset($data['options']))    $field->setOptions($data['options']);

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
