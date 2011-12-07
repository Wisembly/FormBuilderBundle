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
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldAnswerInterface;
use Balloon\Bundle\FormBuilderBundle\Model\FormFieldInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * FormFieldAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class FormFieldAnswer implements FormFieldAnswerInterface
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
     * @ORM\Column(name="value", type="array")
     */
    private $value;

    /**
     * @var FormItem $field
     *
     * @ORM\ManyToOne(targetEntity="FormField", inversedBy="form")
     */
    private $field;

    /**
     * @var FormItem $field
     *
     * @ORM\ManyToOne(targetEntity="FormAnswer", inversedBy="fieldAnswers")
     */
    private $answer;

    public function setField(FormFieldInterface $field)
    {
        $this->field = $field;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getScalarValue()
    {
        switch (gettype($this->value)) {
            case 'object':
                if ($this->value instanceof \DateTime) {
                    $dateFormatter = \IntlDateFormatter::create(
                        \Locale::getDefault(),
                        \IntlDateFormatter::SHORT,
                        \IntlDateFormatter::SHORT,
                        date_default_timezone_get(),
                        \IntlDateFormatter::GREGORIAN
                    );

                    return $dateFormatter->format($this->value);
                }

                return $this->value;
            case 'array':
                $choices = array();

                $options = $this->field->getOptions();
                foreach ($options['choices'] as $k => $v) {
                    if (false !== array_search($k, $this->value)) {
                        $choices[] = $v;
                    }
                }

                return implode(', ', $choices);
            case 'boolean':
                return $this->value ? '~' : '';
            default:
                if ($this->field->getType() == 'choice') {
                    $choices = $this->field->getOption('choices');

                    return $choices[$this->value];
                }

                return $this->value;
        }
    }

    public function setAnswer(FormAnswerInterface $answer)
    {
        $this->answer = $answer;
    }
}
