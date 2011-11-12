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
        $form = $this->get('balloon_form_manager')->find($formid);

        if (null === $form) {
            $form = $this->get('balloon_form_factory')->formInstance();
        }

        $formForm = $this->createFormBuilder($form)->add('name')->getForm();

        $fieldsForm = $this->get('balloon_form_builder')->build($formid, true);

        if ('POST' === $this->getRequest()->getMethod()) {
            $formForm->bindRequest($this->getRequest());

            if ($formForm->isValid()) {
                $this->get('balloon_form_decoder')->decode($form, $fieldsForm->getData());
                $this->getDoctrine()->getEntityManager()->persist($form);
                $this->getDoctrine()->getEntityManager()->flush();

                return $this->redirect($this->generateUrl('form_list'));
            }
        }

        return $this->render('BalloonFormBuilderBundle:Form:edit.html.twig', array(
            'formid' => $formid,
            'fields' => $fieldsForm->createView(),
            'form'   => $formForm->createView(),
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

    public function answerAction($formid)
    {
        $form = $this->get('balloon_form_manager')->find($formid);

        $fields = $this->get('balloon_form_builder')->build($formid);

        if ('POST' === $this->getRequest()->getMethod()) {
            $formForm->bindRequest($this->getRequest());

            if ($formForm->isValid()) {
                $this->get('balloon_form_decoder')->decode($form, $fields->getData());
                $this->getDoctrine()->getEntityManager()->persist($form);
                $this->getDoctrine()->getEntityManager()->flush();

                return $this->redirect($this->generateUrl('form_list'));
            }
        }

        return $this->render('BalloonFormBuilderBundle:Form:answer.html.twig', array(
            'fields' => $fields->createView(),
        ));
    }
}
