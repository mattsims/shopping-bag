<?php

namespace Laraware\Bag;

use Exception;

class BagDiscount
{
    protected $hash;

    protected $name;

    protected $price;

    protected $attributes;

    public function __construct($name, $price, array $attributes = [])
    {
        $this->assertNameIsString($name);

        $this->setName($name);

        $this->assertPriceIsFloat($price);

        $this->assertPriceIsGreaterThanZero($price);

        $this->setPrice($price);

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
        $this->hash = sha1($this->getName().$this->getPrice().json_encode($this->getAttributes()));
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

    public function getPrice()
    {
        return $this->price;
    }

    protected function setPrice(float $price)
    {
        $this->price = -$price;
    }

    protected function assertPriceIsGreaterThanZero($price)
    {
        if ($price == 0) {
            throw new Exception();
        }
    }

    protected function assertPriceIsFloat($price)
    {
        if (!is_float($price)) {
            throw new Exception();
        }
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
