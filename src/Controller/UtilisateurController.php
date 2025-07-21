<?php

namespace App\Controller;

use App\Entity\User;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\Request;

final class UtilisateurController extends AbstractController
{
    #[OA\Get(
        path: '/api/utilisateur',
        summary: 'Liste des utilisateurs',
        tags: ['Utilisateurs'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'La page que l’on veut récupérer',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                required: false,
                description: 'Le nombre d’éléments à récupérer',
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retourne la liste des utilisateurs',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(
                        type: User::class,
                        groups: ['getUsers']
                    ))
                )
            )
        ]
    )]
    #[IsGranted("ROLE_ADMINISTRATEUR", message: 'Vous n\'avez pas les droits
suffisants')]
    #[Route('/api/utilisateur', name: 'app_utilisateur', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository, Request
    $request): Response
    {
        // On récupère les paramètres de pagination
        // On utilise le paramètre 'page' pour la pagination
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $users = $userRepository->findAll();
        // On utilise la méthode json() pour retourner les utilisateurs avec un code HTTP 200
        return $this->json($users, Response::HTTP_OK, [], ['groups' =>
        ['getUsers']]);
    }
    #[OA\Get(
        path: '/api/utilisateur/filter',
        summary: 'Filtre les utilisateurs par rôle',
        tags: ['Utilisateurs'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'La page que l’on veut récupérer',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                required: false,
                description: 'Le nombre d’éléments à récupérer',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'role',
                in: 'query',
                required: false,
                description: 'Filtrer par rôle (ex: ROLE_MANAGER,
ROLE_CONSULTANT, ROLE_ADMINISTRATEUR)',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retourne la liste des utilisateurs',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(
                        type: User::class,
                        groups: ['getUsers']
                    ))
                )
            )
        ]
    )]
    #[Route(
        '/api/utilisateur/filter',
        name: 'app_utilisateur_filter',
        methods: ['GET']
    )]
    #[IsGranted("ROLE_ADMINISTRATEUR", message: 'Vous n\'avez pas les droits
suffisants')]
    public function getAllUsersByRoles(UserRepository $userRepository, Request
    $request): Response
    {
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 10);
        $role = $request->query->get('role'); // Ex: ROLE_MANAGER
        $users = $userRepository->findAllWithPaginationAndRole(
            $page,
            $limit,
            $role
        );
        return $this->json($users, Response::HTTP_OK, [], ['groups' =>
        ['getUsers']]);
    }
}
