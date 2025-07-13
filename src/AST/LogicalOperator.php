<?php

namespace Blessing\LaravelSearchString\AST;

enum LogicalOperator: string
{
    case And = 'and';
    case Or = 'or';
}
