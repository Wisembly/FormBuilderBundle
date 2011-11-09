<?php

namespace Balloon\Bundle\FormBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;

/**
 * Balloon\Bundle\FormBuilderBundle\Entity\Form
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Form implements FormInterface
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $name
     *
     * @ORM\OneToMany(targetEntity="FormField", mappedBy="form", cascade={"persist", "remove", "merge"})
     */
    protected $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Form
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function resetFields()
    {
        $this->fields->clear();
    }

    public function getFields()
    {
        return $this->fields->toArray();
    }

    public function addField(FormFieldInterface $field)
    {
        $field->setForm($this);

        $this->fields->add($field);
    }
}
