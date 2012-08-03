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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldAnswerInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormAnswerInterface;

/**
 * FormAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
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

    public function __construct()
    {
        $this->fieldAnswers = new ArrayCollection();
    }

    public function setForm(FormInterface $form)
    {
        $this->form = $form;
    }

    public function getFieldAnswers()
    {
        return $this->fieldAnswers;
    }

    public function addFieldAnswer(FormFieldAnswerInterface $fieldAnswer)
    {
        $fieldAnswer->setAnswer($this);

        $this->fieldAnswers->add($fieldAnswer);
    }
}
