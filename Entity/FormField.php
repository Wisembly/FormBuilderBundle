<?php

namespace Balloon\Bundle\FormBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

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
    private $options;

    /**
     * @var string $form
     *
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="fields")
     */
    private $form;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Get form
     *
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }
}