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

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Manager
 *
 * @author Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class Manager
{
    protected $objectManager;
    protected $repository;

    public function __construct(ObjectManager $objectManager, $repositoryName)
    {
        $this->objectManager    = $objectManager;
        $this->repository       = $this->objectManager->getRepository($repositoryName);
    }

    public function getRepository()
    {
        // @codeCoverageIgnoreStart
        return $this->repository;
        // @codeCoverageIgnoreEnd
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}
