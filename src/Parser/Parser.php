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

    /**
     * @return list<AST\Comparison|AST\Logical|AST\SearchWords>
     */
    public function parse(string $input): array
    {
        $this->lexer->setInput($input);

        $logicals = [];
        while ($this->lexer->glimpse() !== null) {
            array_push($logicals, $this->parseLogical(self::PREC_OR));
        }

        return $logicals;
    }

    protected function parseComparison(): AST\Comparison|AST\SearchWords
    {
        $this->lexer->moveNext();
        $fieldName = match ($this->lexer->lookahead->type) {
            TokenType::Ident, TokenType::String, TokenType::Keyword => $this->lexer->lookahead->value,
            default => throw SyntaxError::expected($this->lexer->lookahead, 'field name'),
        };

        $operator = match ($this->lexer->glimpse()?->type) {
            TokenType::Eq, TokenType::Colon => AST\ComparisonOperator::Eq,
            TokenType::Gt => AST\ComparisonOperator::Gt,
            TokenType::Lt => AST\ComparisonOperator::Lt,
            TokenType::Ge => AST\ComparisonOperator::Ge,
            TokenType::Le => AST\ComparisonOperator::Le,
            default => null,
        };
        if ($operator === null) {
            return new AST\SearchWords($fieldName);
        } else {
            $this->lexer->moveNext();
        }

        $this->lexer->moveNext();
        $fieldValue = match ($this->lexer->lookahead->type) {
            TokenType::Ident, TokenType::Number, TokenType::String => $this->lexer->lookahead->value,
            default => throw SyntaxError::expected($this->lexer->lookahead, 'field value'),
        };

        return new AST\Comparison($fieldName, $operator, $fieldValue);
    }

    protected function parseComparisonOrParen(): AST\Comparison|AST\SearchWords|AST\Parenthesized
    {
        if ($this->lexer->glimpse()->type === TokenType::Lparen) {
            $this->lexer->moveNext();
            $logicals = [];
            while ($this->lexer->glimpse()->type !== TokenType::Rparen) {
                array_push($logicals, $this->parseLogical(self::PREC_OR));
            }
            $this->lexer->moveNext();

            return new AST\Parenthesized($logicals);
        } else {
            return $this->parseComparison();
        }
    }

    protected function parseLogical(int $precedence): AST\Logical|AST\Comparison|AST\SearchWords|AST\Parenthesized
    {
        if ($precedence === self::PREC_AND) {
            $left = $this->parseComparisonOrParen();
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
                $right = $this->parseComparisonOrParen();
            } else {
                $right = $this->parseLogical($precedence + 1);
            }
            $left = new AST\Logical($left, $operator, $right);
        }

        return $left;
    }
}
