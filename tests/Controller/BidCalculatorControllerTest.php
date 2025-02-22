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

    public function testIndex(): void
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
}
