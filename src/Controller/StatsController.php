<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StatsController extends AbstractController
{
    #[Route('/api/stats', name: 'api_stats', methods: ['GET'])]
    public function getStats(MemberRepository $memberRepository): JsonResponse
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        return $this->json([
            'month' => $memberRepository->getStats($currentYear, $currentMonth),
            'year' => $memberRepository->getStats($currentYear, null),
            'global' => $memberRepository->getStats(null, null),
        ],Response::HTTP_OK,[]);
    }

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
