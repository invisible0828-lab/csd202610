<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\Calculator;  // ← まだ存在しないクラス！
use InvalidArgumentException;

class CalculatorTest extends TestCase
{
    /**
     * 2つの数を足し算できることをテストする
     */
    #[Test]
    public function it_can_add_two_numbers()
    {
        // Arrange（準備）: テストに必要なオブジェクトを用意
        $calculator = new Calculator();

        // Act（実行）: テストしたい機能を実行
        $result = $calculator->add(2, 3);

        // Assert（検証）: 期待通りの結果になっているか確認
        $this->assertEquals(5, $result);
    }

    /**
     * 2つの数を掛け算できることをテストする
     */
    #[Test]
    public function it_can_multiply_two_numbers()
    {
    $calculator = new Calculator();
    $result = $calculator->multiply(3, 4);
    $this->assertEquals(12, $result);
    }

    /**
     * 引き算ができることをテストする
    */
    #[Test]
    public function it_can_subtract_two_numbers()
    {
    // Arrange
    $calculator = new Calculator();

    // Act
    $result = $calculator->subtract(10, 3);

    // Assert - 結果が正の数であることを確認
    $this->assertTrue($result > 0);

    // Assert - 結果が負の数でないことを確認
    $this->assertFalse($result < 0);

    // Assert - 正確な値も確認
    $this->assertEquals(7, $result);
    }


    /**
     * 割り算ができることをテストする
     */
    #[Test]
    public function it_can_divide_two_numbers()
    {
    // Arrange
    $calculator = new Calculator();

    // Act
    $result = $calculator->divide(10, 2);

    // Assert
    $this->assertEquals(5, $result);
    }

    /**
     * 0で割ると例外が投げられることをテストする
     */
    #[Test]
    public function it_throws_exception_when_dividing_by_zero()
    {
    // Arrange
    $calculator = new Calculator();

    // Assert - 例外が投げられることを期待
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('0で割ることはできません');

    // Act - 例外が投げられるはず
    $calculator->divide(10, 0);
    }
    
}
