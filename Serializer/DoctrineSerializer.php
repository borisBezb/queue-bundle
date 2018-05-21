<?php

namespace Bezb\QueueBundle\Serializer;

use Bezb\QueueBundle\JobInterface;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSerializer implements SerializerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param JobInterface $job
     * @return string
     * @throws \ReflectionException
     */
    public function serialize(JobInterface $job): string
    {
        $properties = (new \ReflectionClass($job))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $value = $property->getValue($job);

            if (!is_object($value)) {
                continue;
            }

            $className = get_class($value);

            try {
                $meta = $this->entityManager->getClassMetadata($className);

                $property->setValue($job, new SerializedEntity(
                    $className,
                    $meta->getIdentifierValues($job)
                ));
            } catch (MappingException $e) {}
        }

        return serialize($job);
    }

    /**
     * @param string $string
     * @return JobInterface
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function unserialize(string $string): JobInterface
    {
        $job = unserialize($string);

        if (!is_object($job)) {
            throw new \Exception('Unserialized job is not object');
        }

        $properties = (new \ReflectionClass($job))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($job);

            if (!($value instanceof SerializedEntity)) {
                continue;
            }

            $entity = $this->entityManager
                ->getRepository($value->getClassName())
                ->findOneBy($value->getIdentifiers())
            ;

            $property->setValue($job, $entity);
        }

        return $job;
    }
}