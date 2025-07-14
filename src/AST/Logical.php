<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

class Logical
{
    public function __construct(
        public Logical|Comparison $left,
        public LogicalOperator $operator,
        public Logical|Comparison $right,
    ) {
    }
}
