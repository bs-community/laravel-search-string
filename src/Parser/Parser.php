<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

use Blessing\LaravelSearchString\AST;

class Parser
{
    protected Lexer $lexer;

    public function __construct(string $input)
    {
        $this->lexer = new Lexer($input);
    }

    public function parse()
    {
        return $this->parseComparison();
    }

    protected function parseComparison(): AST\Comparison
    {
        $this->lexer->moveNext();
        if ($this->lexer->lookahead->type !== TokenType::Ident) {
            throw SyntaxError::expected($this->lexer->lookahead, 'field name');
        }
        $fieldName = $this->lexer->lookahead->value;

        $this->lexer->moveNext();
        $operator = match ($this->lexer->lookahead->type) {
            TokenType::Eq, TokenType::Colon => AST\ComparisonOperator::Eq,
            TokenType::Gt => AST\ComparisonOperator::Gt,
            TokenType::Lt => AST\ComparisonOperator::Lt,
            TokenType::Ge => AST\ComparisonOperator::Ge,
            TokenType::Le => AST\ComparisonOperator::Le,
            default => throw SyntaxError::expected($this->lexer->lookahead, 'operator'),
        };

        $this->lexer->moveNext();
        $fieldValue = match ($this->lexer->lookahead->type) {
            TokenType::Ident, TokenType::Number, TokenType::String => $this->lexer->lookahead->value,
            default => throw SyntaxError::expected($this->lexer->lookahead, 'field value'),
        };

        return new AST\Comparison($fieldName, $operator, $fieldValue);
    }
}
