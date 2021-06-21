<?php

namespace Laraware\Bag;

use Closure;
use Illuminate\Session\SessionManager;
use Laraware\Bag\Concerns\HasCurrencies;
use Laraware\Bag\Concerns\HasFormatting;
use Laraware\Bag\Item\Item;

class Bag
{
    use HasCurrencies;
    use HasFormatting;

    protected $session;

    protected $instance;

    public function __construct(SessionManager $session, string $instance = 'shopping-bag')
    {
        $this->session = $session;

        $this->instance = $instance;
    }

    public function getFormattedTotal()
    {
        return $this->formatValue($this->getTotal());
    }

    public function getFormattedSubTotal()
    {
        return $this->formatValue($this->getSubTotal());
    }

    public function getDiscountsCount()
    {
        return $this->getDiscounts()->count();
    }

    public function getDiscounts()
    {
        $discounts = new BagCollection();

        if ($this->isEmpty()) {
            return $discounts;
        }

        collect($this->get())->map(function ($content) use ($discounts) {
            if ($content instanceof BagDiscount) {
                $discounts->push($content);
            }
        });

        return $discounts;
    }

    public function getNotesCount()
    {
        return $this->getNotes()->count();
    }

    public function getNotes()
    {
        $notes = new BagCollection();

        if ($this->isEmpty()) {
            return $notes;
        }

        collect($this->get())->map(function ($content) use ($notes) {
            if ($content instanceof BagNote) {
                $notes->push($content);
            }
        });

        return $notes;
    }

    public function getChargesCount()
    {
        return $this->getCharges()->count();
    }

    public function getCharges()
    {
        $charges = new BagCollection();

        if ($this->isEmpty()) {
            return $charges;
        }

        collect($this->get())->map(function ($content) use ($charges) {
            if ($content instanceof BagCharge) {
                $charges->push($content);
            }
        });

        return $charges;
    }

    public function getItemsCount()
    {
        return $this->getItems()->count();
    }

    public function getItems()
    {
        $items = new BagCollection();

        if ($this->isEmpty()) {
            return $items;
        }

        collect($this->get())->map(function ($content) use ($items) {
            if ($content instanceof Item) {
                $items->push($content);
            }
        });

        return $items;
    }

    public function getContentCount()
    {
        return $this->get()->count();
    }

    public function getSubTotal()
    {
        $content = $this->get();

        $total = $content->reduce(function ($total, mixed $item) {
            if (!method_exists($item, 'getTotal')) {
                return $total;
            }

            return $total + $item->getTotal();
        }, 0);

        return $total;
    }

    public function getTotal()
    {
        $content = $this->get();

        $total = $content->reduce(function ($total, mixed $item) {
            if ($item instanceof BagNote) {
                return $total;
            }

            if ($item instanceof BagCharge || $item instanceof BagDiscount) {
                return $total + $item->getPrice();
            }

            return $total + $item->getTotal();
        }, 0);

        return $total;
    }

    public function isEmpty()
    {
        return $this->get()->isEmpty();
    }

    public function clear()
    {
        $this->session->put($this->instance, new BagCollection());

        $this->session->save();
    }

    public function search(Closure $search)
    {
        $content = $this->get();

        return $content->filter($search);
    }

    public function find(string $itemHash)
    {
        $content = $this->get();

        if (!$content->has($itemHash)) {
            return new BagCollection();
        }

        return $content->get($itemHash);
    }

    public function remove(string $itemHash)
    {
        $content = $this->get();

        $content->pull($itemHash);

        $this->session->put($this->instance, $content);

        $this->session->save();
    }

    public function update(mixed $item, int $quantity = 1)
    {
        $content = $this->get();

        $content->pull($item->getHash());

        $item->setQuantity($item->getQuantity() + $quantity);

        $content->put($item->getHash(), $item);

        $this->session->put($this->instance, $content);

        $this->session->save();
    }

    public function add(mixed $item)
    {
        $this->assertItemExists($item);

        $content = $this->get();

        $content->put($item->getHash(), $item);

        $this->session->put($this->instance, $content);

        $this->session->save();
    }

    protected function assertItemExists(mixed $item)
    {
        if (!$this->get()->has($item->getHash())) {
            return;
        }

        $this->update($item);
    }

    public function get()
    {
        return $this->session->has($this->instance)
            ? $this->session->get($this->instance)
            : new BagCollection();
    }
}
