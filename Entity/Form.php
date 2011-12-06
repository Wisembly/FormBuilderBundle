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

use Balloon\Bundle\FormBuilderBundle\Model\FormAnswerInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Form
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
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
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string $name
     *
     * @ORM\OneToMany(targetEntity="FormField", mappedBy="form", cascade={"persist", "remove", "merge"})
     */
    protected $fields;

    /**
     * @var Form $form
     *
     * @ORM\OneToMany(targetEntity="FormAnswer", mappedBy="form", cascade={"persist", "remove", "merge"})
     */
    protected $answers;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->answers = new ArrayCollection();
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

    public function getFields()
    {
        return $this->fields;
    }

    public function addField(FormFieldInterface $field)
    {
        $field->setForm($this);

        $this->fields->add($field);
    }

    public function addAnswer(FormAnswerInterface $answer)
    {
        $answer->setForm($this);

        $this->answers->add($answer);
    }

    public function getAnswers()
    {
        return $this->answers;
    }
}
