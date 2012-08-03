<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Field;
use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * FormFieldController
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class FormFieldController extends Controller
{
    public function createAction(Request $request, $type, $formid)
    {
        $formType = $this->get('balloon_form_builder')->getType($type);
        $form = $this->get('balloon_form_builder')->buildType($formType);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->get('balloon_form_storage')->add($formid, $type, $form->getData());

                return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
                // @codeCoverageIgnoreStart
            }
        }
        // @codeCoverageIgnoreEnd

        return $this->render('BalloonFormBuilderBundle:FormField:create.html.twig', array(
            'type' => $type,
            'form' => $form->createView()
        ));
    }

    public function editAction(Request $request, $index, $formid)
    {
        $fieldArr = $this->get('balloon_form_storage')->get($formid, $index);
        $field = $this->get('balloon_form_factory')->fieldInstance()->fromArray($fieldArr);
        $formType = $this->get('balloon_form_builder')->getType($field->getType());
        $form = $this->get('balloon_form_builder')->buildType($formType, $field->getOptions());

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->get('balloon_form_storage')->set($formid, $index, $field->getType(), $form->getData());

                return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
                // @codeCoverageIgnoreStart
            }
        }
        // @codeCoverageIgnoreEnd

        return $this->render('BalloonFormBuilderBundle:FormField:create.html.twig', array(
            'type' => $field->getType(),
            'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, $index, $formid)
    {
        $this->get('balloon_form_storage')->remove($formid, $index);

        return $this->redirect($this->generateUrl('form_edit', array('formid' => $formid)));
    }
}
