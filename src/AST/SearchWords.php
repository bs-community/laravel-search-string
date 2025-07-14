<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

class SearchWords
{
    public string $value;

    public function __construct(string $value)
    {
        if ($value[0] === '"' || $value === "'") {
            $this->value = substr($value, 1, strlen($value) - 2);
        } else {
            $this->value = $value;
        }
    }
}
