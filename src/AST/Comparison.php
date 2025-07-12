<?php

namespace Blessing\LaravelSearchString\AST;

class Comparison
{
    public function __construct(public string $fieldName, public ComparisonOperator $operator, public string $fieldValue)
    {
    }
}
