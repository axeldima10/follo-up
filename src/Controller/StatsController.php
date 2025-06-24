<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

final class StatsController extends AbstractController
{
    
    #[OA\Get(
        path: '/api/stats',
        summary: 'Statistiques Globale',
        tags: ['Statistiques']
    )]
    #[Route('/api/stats', name: 'api_stats', methods: ['GET'])]
    public function getGlobalStats(MemberRepository $memberRepository): JsonResponse
    {
    
        $globalStats = $memberRepository->getStats(null,null);

        return $this->json($globalStats,Response::HTTP_OK,[]);
    }




    #[OA\Get(
    path: '/api/stats/month',
    summary: 'Statistiques du mois en cours',
    tags: ['Statistiques'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Statistiques du mois en cours retournées avec succès',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'total', type: 'integer', example: 85),
                    new OA\Property(property: 'newMembers', type: 'integer', example: 12),
                    new OA\Property(property: 'visitors', type: 'integer', example: 5),
                    new OA\Property(property: 'baptized', type: 'integer', example: 3),
                    new OA\Property(property: 'withTransport', type: 'integer', example: 6),
                    new OA\Property(property: 'inHomeCell', type: 'integer', example: 7),
                ]
            )
        )
    ]
    )]
    #[Route('/api/stats/month', name: 'api_month_stats', methods: ['GET'])]
    public function getCurrentStats(MemberRepository $memberRepository): JsonResponse
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $currentStats = $memberRepository->getStats($currentYear,$currentMonth);

        return $this->json($currentStats,Response::HTTP_OK,[]);
    }


 

    #[OA\Get(
        path: '/api/stats/{year}',
        summary: 'Statistiques par année',
        tags: ['Statistiques']
    )]
    #[Route('/api/stats/{year}', name: 'api_year_stats', methods: ['GET'])]
    public function getStats(MemberRepository $memberRepository): JsonResponse
    {
        $currentYear = (int) date('Y');
        $stats = $memberRepository->getStats($currentYear,null);

        return $this->json($stats,Response::HTTP_OK,[]);
    }

    

    #[OA\Get(
    path: '/api/stats/filter',
    summary: 'Statistiques filtrées par mois et/ou année',
    tags: ['Statistiques'],
    parameters: [
        new OA\Parameter(
            name: 'year',
            in: 'query',
            required: false,
            description: 'Année à filtrer',
            schema: new OA\Schema(type: 'integer', example: 2024)
        ),
        new OA\Parameter(
            name: 'month',
            in: 'query',
            required: false,
            description: 'Mois à filtrer',
            schema: new OA\Schema(type: 'integer', example: 5)
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Statistiques personnalisées retournées avec succès',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'custom', type: 'object', properties: [
                        new OA\Property(property: 'total', type: 'integer', example: 90),
                        new OA\Property(property: 'newMembers', type: 'integer', example: 10),
                        new OA\Property(property: 'visitors', type: 'integer', example: 3),
                        new OA\Property(property: 'baptized', type: 'integer', example: 2),
                        new OA\Property(property: 'withTransport', type: 'integer', example: 5),
                        new OA\Property(property: 'inHomeCell', type: 'integer', example: 4),
                    ])
                ]
            )
        )
    ]
    )]
    #[Route('/api/stats/filter', name: 'api_stats_filter', methods: ['GET'])]
    public function getCustomStats(MemberRepository $memberRepository, Request $request): JsonResponse
    {
        $year = $request->query->get('year');
        $month = $request->query->get('month');

        $year = $year ? (int) $year : null;
        $month = $month ? (int) $month : null;

        return $this->json([
            'custom' => $memberRepository->getStats($year, $month)
        ], Response::HTTP_OK,[]);
    }

    
}
