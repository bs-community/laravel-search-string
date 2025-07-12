<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

use Doctrine\Common\Lexer\Token;
use Error;

class SyntaxError extends Error
{
    public function __construct(public Token $token, string $message)
    {
        $this->message = $message;
    }

    public static function expected(Token $token, string $thing): self
    {
        return new self($token, 'expected '.$thing);
    }
}
