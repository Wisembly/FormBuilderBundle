<?php

namespace Balloon\Bundle\FormBuilderBundle\Entity;

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
     * @ORM\Column(name="value", type="string")
     */
    private $value;

    /**
     * @var FormItem $field
     *
     * @ORM\OneToOne(targetEntity="FormField", inversedBy="form")
     */
    private $field;

    /**
     * @var FormItem $field
     *
     * @ORM\ManyToOne(targetEntity="FormAnswer", inversedBy="fieldAnswers")
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

    public function setField(FormField $field)
    {
        $this->field = $field;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function getAnswer()
    {
        return $this->answer;
    }
}
