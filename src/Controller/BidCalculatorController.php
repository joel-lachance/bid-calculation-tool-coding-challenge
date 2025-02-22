<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Model\BidCalculator;

final class BidCalculatorController extends AbstractController
{
    #[Route('/api/bid-calculator', name: 'app_bid_calculator', methods: ['POST'])]
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        // Get POST data from request
        $data = $request->request->all();

        // Define constraints
        $constraints = new Assert\Collection([
            'base_price' => [new Assert\NotBlank(), new Assert\Type('numeric'), new Assert\Positive()],
            'type' => [new Assert\NotBlank(), new Assert\Choice([BidCalculator::TYPE_CAR_COMMON, BidCalculator::TYPE_CAR_LUXURY])],
        ]);

        // Validate data and return errors if any
        $violations = $validator->validate($data, $constraints);

        if ($violations->count()) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            return $this->json(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }


        // Create BidCalculator instance
        $calculator = new BidCalculator($data['base_price'], $data['type']);

        // Return JSON response
        return $this->json($calculator->getApiResponse());
    }
}
