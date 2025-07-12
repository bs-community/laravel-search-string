<?php

namespace Blessing\LaravelSearchString\AST;

enum ComparisonOperator
{
    case Eq;
    case Gt;
    case Lt;
    case Ge;
    case Le;
}
