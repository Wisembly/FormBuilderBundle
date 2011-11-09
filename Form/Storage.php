<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Symfony\Component\HttpFoundation\Session;

class Storage
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session          = $session;
    }

    public function generateId()
    {
        // we don't take the session_id
        // cuz we can create many forms at the same time
        return rand(0, 1000);
    }

    public function all($formid)
    {
        return $this->_get($formid);
    }

    public function removeAll($formid)
    {
        $this->_remove($formid);
    }

    public function init($formid, array $fields)
    {
        $this->_set($formid, $fields);
    }

    public function add($formid, $type, $data)
    {
        $fields = $this->_get($formid);

        $fields[] = array('type' => $type) + $data;

        $this->_set($formid, $fields);
    }

    public function get($formid, $index)
    {
        $fields = $this->_get($formid);

        return $fields[$index];
    }

    public function set($formid, $index, $type, array $data)
    {
        $fields = $this->_get($formid);

        $fields[$index] = array('type' => $type) + $data;

        $this->_set($formid, $fields);
    }

    public function remove($formid, $index)
    {
        $fields = $this->_get($formid);

        unset($fields[$index]);

        $this->_set($formid, $fields);
    }

    protected function _get($formid)
    {
        return $this->session->get('forms_'.$formid);
    }

    protected function _set($formid, array $fields)
    {
        return $this->session->set('forms_'.$formid, $fields);
    }

    protected function _remove($formid)
    {
        return $this->session->remove('forms_'.$formid);
    }
}
