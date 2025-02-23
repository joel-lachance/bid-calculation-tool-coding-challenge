<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Model\BidCalculator;

final class BidCalculatorControllerTest extends WebTestCase
{
    const FEES_CONFIGURATION = [
        [
            'base_price' => 398.00,
            'type' => BidCalculator::TYPE_CAR_COMMON,
            'basic_buyers_fee' => 39.80,
            'special_fee' => 7.96,
            'association_fee' => 5.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 550.76,
        ],
        [
            'base_price' => 501.00,
            'type' => BidCalculator::TYPE_CAR_COMMON,
            'basic_buyers_fee' => 50.00,
            'special_fee' => 10.02,
            'association_fee' => 10.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 671.02,
        ],
        [
            'base_price' => 57.00,
            'type' => BidCalculator::TYPE_CAR_COMMON,
            'basic_buyers_fee' => 10.00,
            'special_fee' => 1.14,
            'association_fee' => 5.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 173.14,
        ],
        [
            'base_price' => 1800.00,
            'type' => BidCalculator::TYPE_CAR_LUXURY,
            'basic_buyers_fee' => 180.00,
            'special_fee' => 72.00,
            'association_fee' => 15.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 2167.00,
        ],
        [
            'base_price' => 1100.00,
            'type' => BidCalculator::TYPE_CAR_COMMON,
            'basic_buyers_fee' => 50.00,
            'special_fee' => 22.00,
            'association_fee' => 15.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 1287.00,
        ],
        [
            'base_price' => 1000000.00,
            'type' => BidCalculator::TYPE_CAR_LUXURY,
            'basic_buyers_fee' => 200.00,
            'special_fee' => 40000.00,
            'association_fee' => 20.00,
            'storage_fee' => BidCalculator::FIX_STORAGE_FEE,
            'total_fee' => 1040320.00,
        ]

    ];

    public function testGetTotalAndSubtotalFeesFromCollections(): void
    {
        foreach (self::FEES_CONFIGURATION as $config) {
            $config = (object) $config;
            $calculator = new BidCalculator($config->base_price, $config->type);

            $this->assertEquals($config->basic_buyers_fee, round($calculator->getBasicBuyersFee(), 2), "Basic buyer fee is incorrect !");
            $this->assertEquals($config->special_fee, round($calculator->getSpecialFee(), 2), "Special fee is incorrect !");
            $this->assertEquals($config->association_fee, round($calculator->getAssociationFee(), 2), "Association fee is incorrect !");
            $this->assertEquals($config->storage_fee, round($calculator->getFixedStorageFee(), 2), "Fixed storage fee is incorrect !");
            $this->assertEquals($config->total_fee, round($calculator->getTotalFee(), 2), "Total fee is incorrect !");
        }
    }

    /**
     * Constructor
     */
    public function testConstructorWithValidParameters(): void
    {
        $calculator = new BidCalculator(1000.00, BidCalculator::TYPE_CAR_COMMON);
        $this->assertInstanceOf(BidCalculator::class, $calculator);
    }

    public function testConstructorWithInvalidVehicleBasePrice(): void
    {
        $this->expectException(\TypeError::class);
        new BidCalculator('invalid_price', BidCalculator::TYPE_CAR_COMMON);
    }

    public function testConstructorWithInvalidCarType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new BidCalculator(1000.00, 123);
    }

    /**
     * Basic fee
     */
    public function testGetBasicBuyersFeeForCommonCar(): void
    {
        $calculator = new BidCalculator(120.00, BidCalculator::TYPE_CAR_COMMON);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(12.00, $fee);
    }

    public function testGetBasicBuyersFeeMaxForCommonCar(): void
    {
        $calculator = new BidCalculator(10.00, BidCalculator::TYPE_CAR_COMMON);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(10.00, $fee);
    }

    public function testGetBasicBuyersFeeMinForCommonCar(): void
    {
        $calculator = new BidCalculator(1000.00, BidCalculator::TYPE_CAR_COMMON);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(50.00, $fee);
    }

    public function testGetBasicBuyersFeeForLuxuryCar(): void
    {
        $calculator = new BidCalculator(500.00, BidCalculator::TYPE_CAR_LUXURY);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(50.00, $fee);
    }

    public function testGetBasicBuyersFeeMaxForLuxuryCar(): void
    {
        $calculator = new BidCalculator(9999.00, BidCalculator::TYPE_CAR_LUXURY);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(200.00, $fee);
    }

    public function testGetBasicBuyersFeeMinForLuxuryCar(): void
    {
        $calculator = new BidCalculator(100.00, BidCalculator::TYPE_CAR_LUXURY);
        $fee = $calculator->getBasicBuyersFee();

        $this->assertEquals(25.00, $fee);
    }

    /**
     * Special fee
     */
    public function testGetSpecialFeeForCommonCar(): void
    {
        $calculator = new BidCalculator(100.00, BidCalculator::TYPE_CAR_COMMON);
        $fee = $calculator->getSpecialFee();

        $expectedFee = 100.00 * BidCalculator::SPECIAL_FEE_COMMON;

        $this->assertEquals($expectedFee, $fee);
    }

    public function testGetSpecialFeeForLuxuryCar(): void
    {
        $calculator = new BidCalculator(100.00, BidCalculator::TYPE_CAR_LUXURY);
        $fee = $calculator->getSpecialFee();

        $expectedFee = 100.00 * BidCalculator::SPECIAL_FEE_LUXURY;

        $this->assertEquals($expectedFee, $fee);
    }

    /**
     * Association fee
     */
    public function testGetAssociationFee(): void
    {
        $calculator = new BidCalculator(1500.00, BidCalculator::TYPE_CAR_COMMON);
        $fee = $calculator->getAssociationFee();

        $this->assertEquals(15.00, $fee);
    }

    /**
     * Api schema
     */
    public function testGetApiResponse(): void
    {
        $calculator = new BidCalculator(1500.00, BidCalculator::TYPE_CAR_COMMON);
        $response = $calculator->getApiResponse();

        $this->assertArrayHasKey('fees', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('vehicule_base_price', $response);
        $this->assertArrayHasKey('car_type', $response);

        $this->assertEquals(1500.00, $response['vehicule_base_price']);
        $this->assertEquals(BidCalculator::TYPE_CAR_COMMON, $response['car_type']);
        $this->assertArrayHasKey('basic', $response['fees']);
    }
}
