<?php

namespace Laraware\Bag\Item;

use Exception;

class ItemProperty
{
    protected $hash;

    protected $name;

    protected $value;

    protected $attributes;

    public function __construct($name, mixed $value, array $attributes = [])
    {
        $this->assertNameIsString($name);

        $this->setName($name);

        $this->setValue($value);

        $this->assertAttributesIsArray($attributes);

        $this->setAttributes($attributes);

        $this->setHash();
    }

    public function getHash()
    {
        return $this->hash;
    }

    protected function setHash()
    {
        $this->hash = sha1($this->getName().$this->getValue().json_encode($this->getAttributes()));
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    protected function assertAttributesIsArray($attributes)
    {
        if (!is_array($attributes)) {
            throw new Exception();
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function setValue(mixed $value)
    {
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function setName(string $name)
    {
        $this->name = $name;
    }

    protected function assertNameIsString($name)
    {
        if (!is_string($name)) {
            throw new Exception();
        }
    }
}
