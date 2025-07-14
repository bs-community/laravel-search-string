<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Tests\Stubs;

use Blessing\LaravelSearchString\Concerns\SearchString;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use SearchString;

    protected array $searchStringColumns = ['name', 'email'];
}
