<?php

use Laraware\Bag\Item\Item;
use Laraware\Bag\Item\ItemProperty;
use PHPUnit\Framework\TestCase;

final class ItemGetPropertyValueTest extends TestCase
{
    public function testItemGetPropertyValue(): void
    {
        $propertyName = 'foo';
        $propertyValue = 'bar';

        $item = new Item('Test Item', 1.00, 1);

        $this->assertInstanceOf(Item::class, $item);

        $entryProperty = new ItemProperty($propertyName, $propertyValue);

        $this->assertInstanceOf(ItemProperty::class, $entryProperty);

        $item->setProperties($entryProperty);

        $this->assertIsArray($item->getProperties());

        $this->assertCount(1, $item->getProperties());

        $this->assertSame($propertyValue, $item->getPropertyValue($propertyName));
    }
}
