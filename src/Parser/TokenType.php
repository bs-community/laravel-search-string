<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

enum TokenType
{
    case Unknown;
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
    case Dash;
    case Lparen;
    case Rparen;
    case AmpAmp;
    case BarBar;
}
