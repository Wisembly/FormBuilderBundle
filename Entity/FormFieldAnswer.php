<?php

namespace Balloon\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Balloon\FormBundle\Entity\FormItemAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormFieldAnswer
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
     * @var FormItem $field
     *
     * @ORM\ManyToOne(targetEntity="FormItem", inversedBy="form")
     */
    private $field;

    /**
     * @var FormItem $field
     *
     * @ORM\ManyToOne(targetEntity="FormAnswer", inversedBy="fields")
     */
    private $answer;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setField(FormItem $field)
    {
        $this->field = $field;
    }
}
