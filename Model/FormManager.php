<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session;

class FormManager
{
    private $session;
    private $objectManager;
    private $repository;

    public function __construct(Session $session, ObjectManager $objectManager, $repository)
    {
        $this->session          = $session;
        $this->objectManager    = $objectManager;
        $this->repository       = $this->objectManager->getRepository($repository);
    }

    public function find($id)
    {
        if (null !== ($fields = $this->session->get('forms_'.$id))) {
            return $fields;
        }

        if (null !== ($form = $this->repository->find($id))) {
            $fieldsArr = array();

            foreach ($form->getFields() as $field) {
                $fieldsArr[] = array('type' => $field->getType()) + $field->getOptions();
            }

            $this->session->set('forms_'.$id, $fieldsArr);

            return $fieldsArr;
        }

        throw new \ErrorException('should not happen');
    }

    public function addField($formid, $type, array $data)
    {
        $fields = $this->session->get('forms_'.$formid);

        $fields[] = array('type' => $type) + $data;

        $this->session->set('forms_'.$formid, $fields);
    }

    public function getField($formid, $index)
    {
        $form = $this->session->get('forms_'.$formid);

        return $form[$index];
    }

    public function updateField($formid, $index, $type, array $data)
    {
        $fields = $this->session->get('forms_'.$formid);

        $fields[$index] = array('type' => $type) + $data;

        $this->session->set('forms_'.$formid, $fields);
    }

    public function removeField($formid, $index)
    {
        $fields = $this->session->get('forms_'.$formid);

        unset($fields[$index]);

        $this->session->set('forms_'.$formid, $fields);
    }
}
