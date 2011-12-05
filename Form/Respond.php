<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Balloon\Bundle\FormBuilderBundle\Model\FormInterface;
use Doctrine\ORM\EntityManager;

class Respond
{
    private $entityManager;
    private $answerClass;
    private $fieldAnswerClass;

    public function __construct(EntityManager $entityManager, $answerClass, $fieldAnswerClass)
    {
        $this->repository = $entityManager->getRepository($answerClass);
        $this->answerClass = $answerClass;
        $this->fieldAnswerClass = $fieldAnswerClass;
    }

    public function findAll($formid)
    {
        return $this->repository->findByForm($formid);
    }

    public function answer(FormInterface $form, array $data)
    {
        if ($form->getFields()->count() !== count($data)) {
            // @codeCoverageIgnoreStart
            throw new \ErrorException('size of fields should be equals to the size of data');
            // @codeCoverageIgnoreEnd
        }

        $index = 0;
        $fields = $form->getFields();

        $answer = new $this->answerClass;
        $form->addAnswer($answer);

        foreach ($data as $key => $value) {
            $fieldAnswer = new $this->fieldAnswerClass;

            $fields->get($index)->addFieldAnswer($fieldAnswer);
            $fieldAnswer->setValue($value);

            $answer->addFieldAnswer($fieldAnswer);

            $index++;
        }

        return $answer;
    }
}
