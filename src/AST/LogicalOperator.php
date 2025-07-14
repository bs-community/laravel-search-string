<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

enum LogicalOperator: string
{
    case And = 'and';
    case Or = 'or';
}
