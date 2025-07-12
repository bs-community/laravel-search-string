<?php

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
