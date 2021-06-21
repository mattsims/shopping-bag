<?php

namespace Laraware\Bag\Concerns;

trait HasCurrencies
{
    public function getDefaultCurrencySymbol()
    {
        if (!$this->getDefaultCurrency()) {
            return;
        }

        if (!$this->getCurrencies()) {
            return;
        }

        $arrKey = array_search($this->getDefaultCurrency(), array_column($this->getCurrencies(), 'code'));

        $arrCurrency = $this->getCurrencies()[$arrKey];

        return $arrCurrency['symbol'];
    }

    protected function getCurrencies()
    {
        return config('shopping-bag.currencies.collection', []);
    }

    protected function getDefaultCurrency()
    {
        return config('shopping-bag.currencies.default', 'ZAR');
    }
}
