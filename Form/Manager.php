<?php

namespace Balloon\Bundle\FormBuilderBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;

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
