<?php

namespace App\Model;

class BidCalculator
{

    const TYPE_CAR_COMMON = 'common';
    const TYPE_CAR_LUXURY = 'luxury';
    const FIX_STORAGE_FEE = 100;

    private int $_vehicule_base_price = 0;
    private string $_car_type = '';

    public function __construct(float $vehicule_base_price, string $car_type)
    {
        $this->_vehicule_base_price = $vehicule_base_price;
        $this->_car_type = $car_type;
    }

    public function getBasicBuyersFee()
    {
        $price = $this->_vehicule_base_price * 0.1;

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

    public function getSpecialFee()
    {

        switch ($this->_car_type) {
            case self::TYPE_CAR_COMMON:
                $percent = 0.02;
                break;
            case self::TYPE_CAR_LUXURY:
                $percent = 0.04;
                break;
        }

        return $this->_vehicule_base_price * $percent;
    }

    public function getAssociationFee()
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

    public function getFixedStorageFee()
    {
        return self::FIX_STORAGE_FEE;
    }

    public function getTotalFee()
    {
        $feeMethods = [
            'getBasicBuyersFee',
            'getSpecialFee',
            'getAssociationFee',
            'getFixedStorageFee'
        ];

        return array_reduce($feeMethods, function ($fee, $method) {
            return $fee + $this->$method();
        }, 0);
    }

    public function getApiResponse()
    {
        return [
            'fees' => [
                'basic' => $this->getBasicBuyersFee(),
                'special' => $this->getSpecialFee(),
                'association' => $this->getAssociationFee(),
                'fixed' => $this->getFixedStorageFee(),
            ],
            'total' => $this->getTotalFee()
        ];
    }
}
