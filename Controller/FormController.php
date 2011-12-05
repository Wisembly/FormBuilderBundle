<?php

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Field;
use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $fields = $this->get('balloon_form_tallhat')->findFields($formid);
        $fieldsForm = $this->get('balloon_form_builder')->buildFields($fields, false);

        if ('POST' === $this->getRequest()->getMethod()) {
            $formForm->bindRequest($this->getRequest());

            if ($formForm->isValid()) {
                foreach ($fields as $field) {
                    $form->addField($field);
                }

                $this->getDoctrine()->getEntityManager()->persist($form);
                $this->getDoctrine()->getEntityManager()->flush();

                return $this->redirect($this->generateUrl('form_list'));
                // @codeCoverageIgnoreStart
            }
        }
        // @codeCoverageIgnoreEnd

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

    public function answerAction(Request $request, $formid)
    {
        $form = $this->get('balloon_form_manager')->find($formid);
        $fields = $this->get('balloon_form_builder')->build($formid);

        if ('POST' === $request->getMethod()) {
            $fields->bindRequest($request);

            if ($fields->isValid()) {
                $answer = $this->get('balloon_form_respond')->answer($form, $fields->getData());
                $this->getDoctrine()->getEntityManager()->persist($answer);
                $this->getDoctrine()->getEntityManager()->flush();

                $request->getSession()->set('answered_'.$formid, true);
            }
        }

        $answers = $this->get('balloon_form_respond')->findAll($formid);
        $answered = $request->getSession()->get('answered_'.$formid, false);

        return $this->render('BalloonFormBuilderBundle:Form:answer.html.twig', array(
            'form'    => $form,
            'answers' => $answers,
            'answered'=> $answered,
            'fields'  => $fields->createView(),
        ));
    }
}
