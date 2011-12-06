<?php

/*
 * This file is part of the BalloonFormBuilderBundle
 *
 * (c) Balloon <contact@balloonup.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Balloon\Bundle\FormBuilderBundle\Form;

/**
 * Promise
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Promise
{
    private $manager;
    private $storage;
    private $factory;

    public function __construct(Manager $manager, Storage $storage, Factory $factory)
    {
        $this->manager = $manager;
        $this->storage = $storage;
        $this->factory = $factory;
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
            return array_map(array($this->factory->fieldInstance(), 'fromArray'), $fields);
        }

        if (null !== ($form = $this->manager->find($formid))) {
            if ($form->getFields()->count() > 0) {
                $fields = array_map(array($form->getFields()->first(), 'toArray'), $form->getFields()->toArray());
            } else {
                $fields = array();
            }

            // TODO remove me
            $this->storage->init($formid, $fields);

            return $form->getFields()->toArray();
        }

        // @codeCoverageIgnoreStart
        throw new \ErrorException("should not happen, formid : $formid");
        // @codeCoverageIgnoreEnd
    }
}
