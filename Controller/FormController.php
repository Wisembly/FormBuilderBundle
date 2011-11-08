<?php

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Balloon\Bundle\FormBuilderBundle\Entity\Field;

class FormController extends Controller
{
    public function listAction()
    {
        $forms = $this->get('balloon_form_manager')->findAll();

        return $this->render('BalloonFormBuilderBundle:Form:list.html.twig', array('forms' => $forms));
    }

    public function createAction()
    {
        $formid = $this->get('balloon_form_storage')->generateId();

        return $this->render('BalloonFormBuilderBundle:Form:create.html.twig', array(
            'formid'    => $formid,
        ));
    }

    public function editAction($formid)
    {
        $fields = $this->get('balloon_form_tallhat')->find($formid);

        $form = $this->get('balloon_form_builder')->buildFields($fields);

        return $this->render('BalloonFormBuilderBundle:Form:edit.html.twig', array(
            'formid' => $formid,
            'form'   => $form->createView(),
        ));
    }

    public function deleteAction($formid)
    {
        $form = $this->get('balloon_form_manager')->find($formid);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($form);
        $em->flush();

        return $this->redirect($this->generateUrl('form_list'));
    }
}
