<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

class TallHat
{
    private $manager;
    private $storage;
    private $encoder;

    public function __construct(Manager $manager, Storage $storage, Encoder $encoder)
    {
        $this->manager = $manager;
        $this->storage = $storage;
        $this->encoder = $encoder;
    }

    /**
     * Search in the session if we have a form corresponding
     *
     * If not fetch it in the db and return an array fields
     *
     * @param integer $formid
     * @return array An array of fields
     */
    public function findFields($formid)
    {
        if (null !== ($fields = $this->storage->all($formid))) {
            return $fields;
        }

        if (null !== ($form = $this->manager->find($formid))) {
            $fields = $this->encoder->encode($form);
            // TODO remove me
            $this->storage->init($formid, $fields);

            return $fields;
        }

        throw new \ErrorException("should not happen, formid : $formid");
    }
}
