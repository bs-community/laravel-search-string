<?php

declare(strict_types=1);

namespace Blessing\LaravelSearchString\Tests;

use Blessing\LaravelSearchString\Tests\Stubs\User;
use PHPUnit\Framework\TestCase;

class ComparisonTest extends TestCase
{
    public function testEq()
    {
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name= "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name ="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name = "root"')->toRawSql());

        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name:"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name: "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name :"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" = \'root\')', User::usingSearchString('name : "root"')->toRawSql());
    }

    public function testGt()
    {
        $this->assertEquals('select * from "users" where ("name" > \'root\')', User::usingSearchString('name>"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" > \'root\')', User::usingSearchString('name> "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" > \'root\')', User::usingSearchString('name >"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" > \'root\')', User::usingSearchString('name > "root"')->toRawSql());
    }

    public function testLt()
    {
        $this->assertEquals('select * from "users" where ("name" < \'root\')', User::usingSearchString('name<"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" < \'root\')', User::usingSearchString('name< "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" < \'root\')', User::usingSearchString('name <"root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" < \'root\')', User::usingSearchString('name < "root"')->toRawSql());
    }

    public function testGe()
    {
        $this->assertEquals('select * from "users" where ("name" >= \'root\')', User::usingSearchString('name>="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" >= \'root\')', User::usingSearchString('name>= "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" >= \'root\')', User::usingSearchString('name >="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" >= \'root\')', User::usingSearchString('name >= "root"')->toRawSql());
    }

    public function testLe()
    {
        $this->assertEquals('select * from "users" where ("name" <= \'root\')', User::usingSearchString('name<="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" <= \'root\')', User::usingSearchString('name<= "root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" <= \'root\')', User::usingSearchString('name <="root"')->toRawSql());
        $this->assertEquals('select * from "users" where ("name" <= \'root\')', User::usingSearchString('name <= "root"')->toRawSql());
    }
}
