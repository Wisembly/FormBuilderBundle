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

    public function setField(FormField $field)
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

                foreach ($this->field->getOption('choices') as $k => $v) {
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

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}
