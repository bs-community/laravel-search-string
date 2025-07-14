<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Builder;

use Blessing\LaravelSearchString\AST;
use Blessing\LaravelSearchString\Parser\Parser;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder
{
    protected Parser $parser;

    public function __construct(protected array $searchStringColumns)
    {
        $this->parser = new Parser();
    }

    public function build(QueryBuilder $query, string $input): QueryBuilder
    {
        $ast = $this->parser->parse($input);
        $this->buildLogicals($query, $ast);

        return $query;
    }

    public function buildLogicals(QueryBuilder $query, array $logicals): QueryBuilder
    {
        foreach ($logicals as $node) {
            if ($node instanceof AST\Comparison && $node->operator === AST\ComparisonOperator::Eq) {
                if ($node->fieldName === 'limit' || $node->fieldName === 'limits') {
                    $query->limit((int) $node->fieldValue);
                    continue;
                } elseif ($node->fieldName === 'from') {
                    $query->offset((int) $node->fieldValue);
                    continue;
                } elseif ($node->fieldName === 'sort') {
                    if ($node->fieldValue[0] === '-') {
                        $query->orderByDesc(substr($node->fieldValue, 1));
                    } else {
                        $query->orderBy($node->fieldValue);
                    }
                    continue;
                }
            }
            if ($node instanceof AST\SearchWords) {
                $columns = $this->searchStringColumns;
                $query->orWhere(function ($query) use ($columns, $node) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%'.$node->value.'%');
                    }
                });
            } elseif ($node instanceof AST\Parenthesized) {
                $query->where(fn ($query) => $this->buildLogicals($query, $node->logicals));
            } else {
                $query->where(function (QueryBuilder $query) use ($node) {
                    if ($node instanceof AST\Comparison) {
                        $query->where($node->fieldName, $node->operator->value, $node->fieldValue);
                    } elseif ($node instanceof AST\Logical) {
                        $this->buildLogical($query, $node);
                    }
                });
            }
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
