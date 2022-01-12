<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * handle all entities mecanism
 */
class EntityServices
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $entityObject
     *
     * @throws Exception
     */
    public function save(object $entityObject)
    {
        if (!method_exists($entityObject, 'getId')) {
            throw new Exception('Entity object must have id property');
        }

        if (!$entityObject->getId()) {
            $this->entityManager->persist($entityObject);
        }

        $this->entityManager->flush();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
