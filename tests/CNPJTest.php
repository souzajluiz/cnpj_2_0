<?php

namespace Souzajluiz\CNPJ\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Souzajluiz\CNPJ\CNPJ;

class CNPJTest extends TestCase
{
    public function test_valid_cnpj_returns_true(): void
    {
        $this->assertTrue(
            CNPJ::isValid('45.723.174/0001-10')
        );
    }

    public function test_invalid_cnpj_returns_false(): void
    {
        $this->assertFalse(
            CNPJ::isValid('11.111.111/1111-11')
        );
    }

    public function test_empty_cnpj_returns_false(): void
    {
        $this->assertFalse(
            CNPJ::isValid('00.000.000/0000-00')
        );
    }

    public function test_calculate_dv(): void
    {
        $this->assertEquals(
            '10',
            CNPJ::calculateDV('457231740001')
        );
    }

    public function test_calculate_dv_throws_exception_for_invalid_cnpj(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CNPJ::calculateDV('000000000000');
    }
}
