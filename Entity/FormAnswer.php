<?php

namespace Balloon\Bundle\FormBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormAnswerInterface;

/**
 * Balloon\FormBundle\Entity\FormAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormAnswer implements FormAnswerInterface
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
     * @var Form $form
     *
     * @ORM\ManyToOne(targetEntity="Form", inversedBy="answers")
     */
    private $form;

    /**
     * @var ArrayCollection $fieldAnswers
     *
     * @ORM\OneToMany(targetEntity="FormFieldAnswer", mappedBy="answer", cascade={"persist", "remove", "merge"})
     */
    private $fieldAnswers;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();

        $this->fieldAnswers = new ArrayCollection();
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

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setForm(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getFieldAnswers()
    {
        return $this->fieldAnswers;
    }

    public function addField($field)
    {
        $field->setAnswer($this);

        $this->fieldAnswers->add($field);
    }
}
