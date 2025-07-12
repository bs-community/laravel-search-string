<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

enum TokenType
{
    case None;
    case Ident;
    case Keyword;
    case Number;
    case String;
    case Eq;
    case Gt;
    case Lt;
    case Ge;
    case Le;
    case Colon;
    case Comma;
    case Lparen;
    case Rparen;
}
