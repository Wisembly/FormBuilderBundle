<?php

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Balloon\Bundle\FormBuilderBundle\Entity\Field;

class FormFieldController extends Controller
{
    public function createAction(Request $request, $type, $id)
    {
        $form = $this->get('balloon_form_builder')->buildType($type);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $this->get('balloon_form_manager')->addField($id, array('type' => $type) + $form->getData());

                return $this->redirect($this->generateUrl('form_edit', array('id' => $id)));
            }
        }

        return $this->render('BalloonFormBuilderBundle:FormField:create.html.twig', array('form' => $form->createView()));
    }
}
