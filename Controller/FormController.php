<?php

namespace Balloon\Bundle\FormBuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Balloon\Bundle\FormBuilderBundle\Entity\Field;

class FormController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BalloonFormBuilderBundle:Form:index.html.twig', array('name' => $name));
    }

    public function createAction()
    {
        $id = rand(0, 1000);
        $form = $this->get('balloon_form_builder')->buildFields(array());

        return $this->render('BalloonFormBuilderBundle:Form:create.html.twig', array(
            'id'    => $id,
            'form'  => $form->createView(),
        ));
    }

    public function editAction($id)
    {
        $fields = $this->get('balloon_form_manager')->find($id);
        var_dump($fields);
        $form = $this->get('balloon_form_builder')->buildFields($fields);

        return $this->render('BalloonFormBuilderBundle:Form:create.html.twig', array(
            'id'    => $id,
            'form'  => $form->createView(),
        ));
    }
}
