<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\AST;

enum ComparisonOperator: string
{
    case Eq = '=';
    case Gt = '>';
    case Lt = '<';
    case Ge = '>=';
    case Le = '<=';
}
