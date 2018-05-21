<?php

namespace Bezb\QueueBundle\Serializer;

class SerializedEntity
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $identifiers;

    /**
     * SerializedEntity constructor.
     * @param string $className
     * @param array $identifiers
     */
    public function __construct(string $className, array $identifiers)
    {
        $this->className = $className;
        $this->identifiers = $identifiers;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }
}