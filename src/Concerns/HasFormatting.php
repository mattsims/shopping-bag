<?php

namespace Laraware\Bag\Concerns;

trait HasFormatting
{
    protected function formatValue(float $value)
    {
        return $this->getDefaultCurrencySymbol().number_format($value, $this->getDecimals(), $this->getDecimalSeparator(), $this->getThousandsSeparator());
    }

    protected function shouldFormatValues()
    {
        return config('shopping-bag.formatting.format_values', true);
    }

    protected function getThousandsSeparator()
    {
        return config('shopping-bag.formatting.thousands_separator', ',');
    }

    protected function getDecimalSeparator()
    {
        return config('shopping-bag.formatting.decimal_separator', '.');
    }

    protected function getDecimals()
    {
        return config('shopping-bag.formatting.decimals', 2);
    }
}
