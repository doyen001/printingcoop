<?php

namespace Tests\Unit\Services\Pricing;

use Tests\TestCase;
use App\Services\Pricing\PriceCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    protected $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PriceCalculator();
    }

    /**
     * Test basic price calculation with base price only
     */
    public function test_basic_price_calculation()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 2,
        ];

        $result = $this->calculator->calculatePrice($params);

        $this->assertEquals(1, $result['success']);
        $this->assertEquals('200.00', $result['price']);
    }

    /**
     * Test price calculation with recto verso percentage
     * Lines 857-860: if recto_verso == "Yes", add percentage to price
     */
    public function test_recto_verso_pricing()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 1,
            'recto_verso' => 'Yes',
            'recto_verso_price' => 20, // 20% increase
        ];

        $result = $this->calculator->calculatePrice($params);

        // Base price: 100
        // With 20% recto verso: 100 + (100 * 20 / 100) = 120
        $this->assertEquals('120.00', $result['price']);
    }

    /**
     * Test width/length validation - empty length
     * Lines 538-543
     */
    public function test_width_length_validation_empty_length()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 1,
            'add_length_width' => 1,
            'product_length' => '',
            'product_width' => 10,
        ];

        $result = $this->calculator->calculatePrice($params, 'English');

        $this->assertEquals('', $result['product_length']);
        $this->assertEquals('Please enter length', $result['product_length_error']);
    }

    /**
     * Test width/length validation - French error messages
     * Lines 541-543
     */
    public function test_width_length_validation_french()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 1,
            'add_length_width' => 1,
            'product_length' => '',
            'product_width' => 10,
        ];

        $result = $this->calculator->calculatePrice($params, 'French');

        $this->assertEquals('Veuillez saisir la longueur', $result['product_length_error']);
    }

    /**
     * Test depth calculation with volume (length * width * depth)
     * Line 689: rq_area = product_depth_length * product_depth_width * product_depth
     */
    public function test_depth_volume_calculation()
    {
        // This test would require database setup with product data
        // Skipping for now - demonstrates test structure
        $this->markTestSkipped('Requires database setup');
    }

    /**
     * Test page calculation with pages and sheets
     * Lines 826-836
     */
    public function test_page_calculation_with_pages_and_sheets()
    {
        // This test would require database setup with product data
        // Skipping for now - demonstrates test structure
        $this->markTestSkipped('Requires database setup');
    }

    /**
     * Test multiple attributes extraction and sorting
     * Lines 456-480
     */
    public function test_multiple_attributes_extraction()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 1,
            'multiple_attribute_5' => 10,
            'multiple_attribute_2' => 20,
            'multiple_attribute_8' => 30,
        ];

        // Use reflection to test private method
        $reflection = new \ReflectionClass($this->calculator);
        $method = $reflection->getMethod('extractMultipleAttributes');
        $method->setAccessible(true);

        $result = $method->invoke($this->calculator, $params);

        // Should be sorted by attribute_id
        $this->assertEquals([
            [2, 20],
            [5, 10],
            [8, 30],
        ], $result);
    }

    /**
     * Test single attributes extraction
     * Lines 489-500
     */
    public function test_single_attributes_extraction()
    {
        $params = [
            'product_id' => 1,
            'price' => 100.00,
            'quantity' => 1,
            'attribute_id_1' => 5,
            'attribute_id_3' => 7,
        ];

        // Use reflection to test private method
        $reflection = new \ReflectionClass($this->calculator);
        $method = $reflection->getMethod('extractSingleAttributes');
        $method->setAccessible(true);

        $result = $method->invoke($this->calculator, $params);

        $this->assertEquals([
            [1, 5],
            [3, 7],
        ], $result);
    }

    /**
     * Test showValue formatting
     */
    public function test_show_value_formatting()
    {
        $reflection = new \ReflectionClass($this->calculator);
        $method = $reflection->getMethod('showValue');
        $method->setAccessible(true);

        $this->assertEquals('10.50', $method->invoke($this->calculator, 10.5));
        $this->assertEquals('100.00', $method->invoke($this->calculator, 100));
        $this->assertEquals('0.99', $method->invoke($this->calculator, 0.99));
    }

    /**
     * Test color pricing - black vs color
     * Lines 577-587
     */
    public function test_color_pricing_calculation()
    {
        // This test demonstrates the color pricing logic
        // Actual test would require database setup
        $this->markTestSkipped('Requires database setup');
    }

    /**
     * Test quantity multiplier
     * Lines 589-591
     */
    public function test_quantity_multiplier()
    {
        // This test demonstrates quantity multiplication
        // Actual test would require database setup
        $this->markTestSkipped('Requires database setup');
    }
}
