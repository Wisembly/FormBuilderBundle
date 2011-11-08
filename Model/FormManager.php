<?php

namespace Balloon\Bundle\FormBuilderBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session;

class FormManager
{
    private $session;
    private $objectManager;
    private $repository;

    public function __construct(Session $session, ObjectManager $objectManager, $repository = 'BalloonFormBuilder:Form')
    {
        $this->session          = $session;
        $this->objectManager    = $objectManager;
        $this->repository       = $this->objectManager->getRepository($repository);
    }

    public function find($id)
    {
//        if (null !== ($fields = $this->repository->find($id))) {
            $fieldsArr = array();

            //foreach ($fields as $field) {
               // TODO 
            //}

    //        return $fields;
  //      }

        if (null !== ($fields = $this->session->get('forms_'.$id))) {
            foreach ($fields as $k => $field) {
                $type = $field['type'];
                unset($field['type']);
                $options = $field['options'];

                $field = new FormField()
            }
            return $fields;
        }

        throw new \ErrorException('should not happen');
    }

    public function addField($formid, array $fields)
    {
        $fields = $this->session->get('forms_'.$formid);

        $fields[] = $fields;

        $this->session->set('forms_'.$formid, $fields);
    }
}
