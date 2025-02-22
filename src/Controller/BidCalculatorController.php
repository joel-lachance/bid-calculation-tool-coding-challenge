<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\BidCalculator;

final class BidCalculatorController extends AbstractController
{
    #[Route('/bid-calculator', name: 'app_bid_calculator')]
    public function index(): Response
    {
        $calculator = new BidCalculator(700, BidCalculator::TYPE_CAR_COMMON);
        dd(
            $calculator->getBasicBuyersFee(),
            $calculator->getSpecialFee(),
            $calculator->getAssociationFee(),
            $calculator->getFixedStorageFee(),
            $calculator->getTotalFee(),
            $calculator->getApiResponse()
        );
    }
}
