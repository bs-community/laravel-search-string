<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Parser;

use Blessing\LaravelSearchString\AST;

class Parser
{
    private const PREC_OR = 0;
    private const PREC_AND = 1;

    protected Lexer $lexer;

    public function __construct()
    {
        $this->lexer = new Lexer();
    }

    public function parse(string $input)
    {
        $this->lexer->setInput($input);

        return $this->parseLogical(self::PREC_OR);
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

    protected function parseLogical(int $precedence): AST\Logical|AST\Comparison
    {
        if ($precedence === self::PREC_AND) {
            $left = $this->parseComparison();
        } else {
            $left = $this->parseLogical($precedence + 1);
        }

        while ($token = $this->lexer->glimpse()) {
            if ($precedence === self::PREC_AND
                && ($token->type === TokenType::Keyword && $token->value === 'and' || $token->type === TokenType::AmpAmp)) {
                $operator = AST\LogicalOperator::And;
            } elseif ($precedence === self::PREC_OR
                && ($token->type === TokenType::Keyword && $token->value === 'or' || $token->type === TokenType::BarBar)) {
                $operator = AST\LogicalOperator::Or;
            } else {
                return $left;
            }
            $this->lexer->moveNext();

            if ($precedence === self::PREC_AND) {
                $right = $this->parseComparison();
            } else {
                $right = $this->parseLogical($precedence + 1);
            }
            $left = new AST\Logical($left, $operator, $right);
        }

        return $left;
    }
}
