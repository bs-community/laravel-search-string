<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

class Comparison
{
    public string $fieldName;
    public string $fieldValue;

    public function __construct(string $fieldName, public ComparisonOperator $operator, string $fieldValue)
    {
        if ($fieldName[0] === '"' || $fieldName === "'") {
            $this->fieldName = substr($fieldName, 1, strlen($fieldName) - 2);
        } else {
            $this->fieldName = $fieldName;
        }

        if ($fieldValue[0] === '"' || $fieldValue === "'") {
            $this->fieldValue = substr($fieldValue, 1, strlen($fieldValue) - 2);
        } else {
            $this->fieldValue = $fieldValue;
        }
    }
}
