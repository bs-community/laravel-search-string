<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

use Doctrine\Common\Lexer\AbstractLexer;

class Lexer extends AbstractLexer
{
    protected function getCatchablePatterns(): array
    {
        return [
            "'(?:[^'])*'", '"(?:[^"])*"',
            '[a-zA-Z_][a-zA-Z0-9_\-\/]*',
            '[+-]?\d+(?:\.\d+)?',
            '=', ':', '>=', '>', '<=', '<', ',', '-', '\(', '\)', '&&', '||',
        ];
    }

    protected function getNonCatchablePatterns(): array
    {
        return ['\s+', '(.)'];
    }

    protected function getType(string &$value): TokenType
    {
        if ($value[0] === '"' || $value[0] === "'") {
            return TokenType::String;
        } elseif (is_numeric($value)) {
            return TokenType::Number;
        } elseif (ctype_alnum($value[0]) || $value[0] === '-' || $value[0] === '_') {
            return match ($value) {
                'or', 'and', 'not' => TokenType::Keyword,
                default => TokenType::Ident,
            };
        } else {
            return match ($value) {
                '=' => TokenType::Eq,
                ':' => TokenType::Colon,
                '>=' => TokenType::Ge,
                '>' => TokenType::Gt,
                '<=' => TokenType::Le,
                '<' => TokenType::Lt,
                ',' => TokenType::Comma,
                '-' => TokenType::Dash,
                '(' => TokenType::Lparen,
                ')' => TokenType::Rparen,
                '&&' => TokenType::AmpAmp,
                '||' => TokenType::BarBar,
                default => TokenType::None,
            };
        }
    }
}
