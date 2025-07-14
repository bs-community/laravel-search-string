<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

class Parenthesized
{
    public function __construct(public array $logicals)
    {
    }
}
