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

    public function find($formid)
    {
        if (null !== ($fields = $this->storage->all($formid))) {
            return $fields;
        }

        if (null !== ($form = $this->manager->find($formid))) {
            $fields = $this->encoder->encode($form);
            $this->storage->init($formid, $fields);

            return $fields;
        }

        throw new \ErrorException('should not happen');
    }
}
