<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Builder;

use Blessing\LaravelSearchString\AST;
use Blessing\LaravelSearchString\Parser\Parser;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Builder
{
    protected Parser $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    public function build(QueryBuilder $query, string $input): QueryBuilder
    {
        $ast = $this->parser->parse($input);
        if ($ast instanceof AST\Comparison) {
            $query->where($ast->fieldName, $ast->operator->value, $ast->fieldValue);
        } elseif ($ast instanceof AST\Logical) {
            $this->buildLogical($query, $ast);
        }

        return $query;
    }

    protected function buildLogical(QueryBuilder $query, AST\Logical $ast): void
    {
        if ($ast->left instanceof AST\Comparison) {
            $query->where($ast->left->fieldName, $ast->left->operator->value, $ast->left->fieldValue);
        } elseif ($ast->left instanceof AST\Logical) {
            $this->buildLogical($query, $ast->left);
        }
        $query->where(function ($query) use ($ast) {
            if ($ast->right instanceof AST\Comparison) {
                $query->where($ast->right->fieldName, $ast->right->operator->value, $ast->right->fieldValue);
            } elseif ($ast->right instanceof AST\Logical) {
                $this->buildLogical($query, $ast->right);
            }
        }, null, null, $ast->operator->value);
    }
}
