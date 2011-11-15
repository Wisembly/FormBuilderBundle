<?php

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Balloon\Bundle\FormBuilderBundle\Entity\Field;

class FormFieldController extends Controller
{
    public function createAction(Request $request, $type, $formid)
    {
        $form = $this->get('balloon_form_builder')->buildType($type);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->get('balloon_form_storage')->add($formid, $type, $form->getData());

                return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
            }
        }

        return $this->render('BalloonFormBuilderBundle:FormField:create.html.twig', array(
            'type' => $type,
            'form' => $form->createView()
        ));
    }

    public function editAction(Request $request, $index, $formid)
    {
        $fieldArr = $this->get('balloon_form_storage')->get($formid, $index);
        $field = $this->get('balloon_form_decoder')->decodeField($fieldArr);
        $form = $this->get('balloon_form_builder')->buildType($field->getType(), $field->getOptions());

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->get('balloon_form_storage')->set($formid, $index, $field->getType(), $form->getData());

                return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
            }
        }

        return $this->render('BalloonFormBuilderBundle:FormField:create.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction(Request $request, $index, $formid)
    {
        $this->get('balloon_form_storage')->remove($formid, $index);

        return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
    }
}
