<?php

namespace App\Model;

class BidCalculator
{

    // Constants properties
    const TYPE_CAR_COMMON = 'common';
    const TYPE_CAR_LUXURY = 'luxury';
    const FIX_STORAGE_FEE = 100;
    const BASIC_BUYERS_FEE_PERCENT = 0.1;
    const SPECIAL_FEE_COMMON = 0.02;
    const SPECIAL_FEE_LUXURY = 0.04;

    // Privates properties
    private int $_vehicule_base_price = 0;
    private string $_car_type = '';

    // Public methods
    public function __construct(float $vehicule_base_price, string $car_type)
    {
        $this->_vehicule_base_price = $vehicule_base_price;
        $this->_car_type = $car_type;
    }

    /**
     * Return the basic buyer's fee depending on the car type
     *
     * @return float
     */
    public function getBasicBuyersFee(): float
    {
        $price = $this->_vehicule_base_price * self::BASIC_BUYERS_FEE_PERCENT;

        switch ($this->_car_type) {
            case self::TYPE_CAR_COMMON:
                $options = ['min' => 10, 'max' => 50];
                break;
            case self::TYPE_CAR_LUXURY:
                $options = ['min' => 25, 'max' => 200];
                break;
        }

        return max($options['min'], min($options['max'], $price));
    }


    /**
     * Return the special fee depending on the car type
     *
     * @return float
     */
    public function getSpecialFee(): float
    {
        switch ($this->_car_type) {
            case self::TYPE_CAR_COMMON:
                $percent = self::SPECIAL_FEE_COMMON;
                break;
            case self::TYPE_CAR_LUXURY:
                $percent = self::SPECIAL_FEE_LUXURY;
                break;
        }

        return $this->_vehicule_base_price * $percent;
    }

    /**
     * Return the association fee depending on the car price
     *
     * @return float
     */
    public function getAssociationFee(): float
    {
        $price = $this->_vehicule_base_price;

        switch (true) {
            case ($price >= 1 && $price <= 500):
                return 5;
            case ($price > 500 && $price <= 1000):
                return 10;
            case ($price > 1000 && $price <= 3000):
                return 15;
            case ($price > 3000):
                return 20;
            default:
                return 0;
        }
    }


    /**
     * Return the fixed storage fee
     *
     * @return float
     */
    public function getFixedStorageFee(): float
    {
        return self::FIX_STORAGE_FEE;
    }


    /**
     * Return the total fee
     *
     * @return float
     */
    public function getTotalFee(): float
    {
        $feeMethods = [
            'getBasicBuyersFee',
            'getSpecialFee',
            'getAssociationFee',
            'getFixedStorageFee'
        ];

        return array_reduce($feeMethods, function ($fee, $method) {
            return $fee + $this->$method();
        }, $this->_vehicule_base_price);
    }



    /**
     * Return the API response
     *
     * @return array
     */
    public function getApiResponse()
    {
        return [
            'fees' => [
                'basic' => $this->roundFee($this->getBasicBuyersFee()),
                'special' => $this->roundFee($this->getSpecialFee()),
                'association' => $this->roundFee($this->getAssociationFee()),
                'fixed' => $this->roundFee($this->getFixedStorageFee()),
            ],
            'total' => $this->roundFee($this->getTotalFee()),
            'vehicule_base_price' => $this->roundFee($this->_vehicule_base_price),
            'car_type' => $this->_car_type
        ];
    }

    /**
     * Format fee to 2 decimals
     *
     * @param float $fee
     * @return float
     */
    private function roundFee(float $fee): float
    {
        return round($fee, 2);
    }
}
