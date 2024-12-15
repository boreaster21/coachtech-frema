<?php

namespace Tests\Unit;

use Tests\TestCase;

class CalculatorTest extends TestCase
{
    /** @test */
    public function it_can_add_two_numbers()
    {
        // Arrange
        $calculator = new \App\Services\Calculator();

        // Act
        $result = $calculator->add(2, 3);

        // Assert
        $this->assertEquals(5, $result);
    }
}
