<?php

namespace Laraware\Bag\Item;

use Exception;
use Illuminate\Support\Collection;
use Laraware\Bag\Concerns\HasCurrencies;
use Laraware\Bag\Concerns\HasFormatting;

class Item
{
    use HasCurrencies;
    use HasFormatting;

    protected $hash;

    protected $name;

    protected $price;

    protected $quantity;

    protected $notes = [];

    protected $charges = [];

    protected $discounts = [];

    protected $properties = [];

    public function __construct($name, $price, $quantity)
    {
        $this->assertNameIsString($name);

        $this->setName($name);

        $this->assertPriceIsFloat($price);

        $this->assertPriceIsGreaterThanZero($price);

        $this->setPrice($price);

        $this->assertQuantityIsInteger($quantity);

        $this->assertQuantityIsGreaterThanZero($quantity);

        $this->setQuantity($quantity);

        $this->setHash();
    }

    public function getFormattedTotal()
    {
        return $this->formatValue($this->getTotal());
    }

    public function getFormattedSubTotal()
    {
        return $this->formatValue($this->getSubTotal());
    }

    public function getFormattedPrice()
    {
        return $this->formatValue($this->getPrice());
    }

    public function getTotal()
    {
        $total = $this->getSubTotal();

        if ($this->getCharges()) {
            foreach ($this->getCharges() as $charge) {
                $total += $charge->getTotal();
            }
        }

        if ($this->getDiscounts()) {
            foreach ($this->getDiscounts() as $discount) {
                $total += $discount->getPrice();
            }
        }

        return $total;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties(...$properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Get a property value by name.
     *
     * @param  string  $name
     * @return mixed|null
     */
    public function getPropertyValue($name)
    {
        $key = Collection::make($this->properties)
            ->search(function ($property) use ($name) {
                return $name === $property->getName();
            });

        return false !== $key ? $this->properties[$key]->getValue() : null;
    }

    public function getDiscounts()
    {
        return $this->discounts;
    }

    public function setDiscounts(...$discounts)
    {
        $this->discounts = $discounts;

        return $this;
    }

    public function getCharges()
    {
        return $this->charges;
    }

    public function setCharges(...$charges)
    {
        collect($charges)->map(function (ItemCharge $charge) {
            $charge->setQuantity($this->getQuantity());
        });

        $this->charges = $charges;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes(...$notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    protected function setHash()
    {
        $this->hash = sha1($this->getName().$this->getPrice().$this->getQuantity().$this->getSubTotal());
    }

    public function getSubTotal()
    {
        return $this->getPrice() * $this->getQuantity();
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    protected function assertQuantityIsGreaterThanZero($quantity)
    {
        if ($quantity == 0) {
            $this->setQuantity(1);
        }
    }

    protected function assertQuantityIsInteger($quantity)
    {
        if (!is_int($quantity)) {
            throw new Exception();
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    protected function setPrice(float $price)
    {
        $this->price = $price;
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
