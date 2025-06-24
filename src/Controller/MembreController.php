<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Member;
use OpenApi\Attributes as OA;
use App\Repository\MemberRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Attribute\Model;
use JMS\Serializer\DeserializationContext;
use App\Serializer\ExistingObjectConstructor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\ExpressionLanguage\Expression;
//use Nelmio\ApiDocBundle\Attribute\Model as AttributeModel;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

final class MembreController extends AbstractController
{
    
        
    
    #[OA\Get(
        path: '/api/membres',
        summary: 'Liste des membres',
        tags: ['Membres'],
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
                description: 'Retourne la liste des membres',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model (type: Member::class, groups: ['getMembers']))
                )
            )
        ]
    )]
    #[Route('/api/membres', name: 'app_membre', methods:["GET"])]
    public function getAllMembers(MemberRepository $memberRepository, Request $request, SerializerInterface $serializer)
    {
        $page=$request->get('page',1);
        $limit = $request->get('limit',10);
        $members = $memberRepository->findAllWithPagination($page, $limit);
        $context = SerializationContext::create()->setGroups(["getMembers"]);
        $json = $serializer->serialize($members, 'json',$context);
        $response = new JsonResponse($json, Response::HTTP_OK,[
            'Content-Type'=>'application/json'
        ],true);

        return $response;
      

        //return $this->json($memberRepository->findAllWithPagination($page,$limit),Response::HTTP_OK,[],$context);
        //return $this->json($memberRepository->findAll(),Response::HTTP_OK,[],['groups'=>'getMembers'], $context);
        //$response = new JsonResponse($memberRepository->findAllWithPagination($page, $limit),Response::HTTP_OK,[], $context);
        //return $response;
        
    }



    //SHOW DETAILS MEMBER
    #[OA\Get(
        path :"/api/membres/{id}",
        summary: 'Voir le détail des informations d\'un membre',
        tags: ['Membres'],
       
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Retourne le détail du membre demandé',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model (type: Member::class, groups: ['getMembers']))
                )
            )
        ]
    )]
    
    #[Route('/api/membres/{id}', name: 'app_membre_show', methods: ['GET'])]
    public function show(Member $member, SerializerInterface $serializer)
    {
       $context = SerializationContext::create()->setGroups(["getMembers"]);
       $json= $serializer->serialize($member, 'json', $context);
       $response = new JsonResponse($json, Response::HTTP_OK, [
        'Content-Type'=>'application/json'
       ],true);
        return $response;

        //return $this->json($member, Response::HTTP_OK, [], ['groups'=>'getMembers']); 
    
    }


    //DELETE MEMBER 
    
    
    #[OA\Tag(name: 'Membres')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMININSTRATEUR") or is_granted("ROLE_MANAGER")'))]
    #[Route('/api/membres/{id}', name: 'app_member_delete', methods: ['DELETE'])]
    public function deleteMember(Member $member, EntityManagerInterface $em)
    {
        $em->remove($member);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }


    //ADD MEMBER
        #[OA\Post(
        path: '/api/membres',
        summary: 'Créer un nouveau membre',
        tags:['Membres'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Exemple des données du membre à créer',
            
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["firstName", "lastName", "tel", "quartier", "nationalite"],
                    properties: [
                        new OA\Property(property: 'firstName', type: 'string', example: 'Sarah'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'Kouadio'),
                        new OA\Property(property: 'tel', type: 'string', example: '772233445'),
                        new OA\Property(property: 'quartier', type: 'string', example: 'FANN'),
                        new OA\Property(property: 'nationalite', type: 'string', example: 'Côte d\'Ivoire'),
                        new OA\Property(property: 'isMember', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'memberJoinedDate',
                            type: 'string',
                            example: '12/04/2024',
                            description: 'Date d\'adhésion (format jj/mm/aaaa)'
                        ),
                        new OA\Property(property: 'isBaptized', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'baptismDate',
                            type: 'string',
                            example: '01/06/2024',
                            description: 'Date de baptême (format jj/mm/aaaa)'
                        ),
                        new OA\Property(property: 'hasTransport', type: 'boolean', example: false),
                        new OA\Property(
                            property: 'transportDate',
                            type: 'string',
                            nullable: true,
                            example: null,
                            description: 'Date de transport (format jj/mm/aaaa ou null)'
                        ),
                        new OA\Property(property: 'isInHomeCell', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'homeCellJoinDate',
                            type: 'string',
                            example: '15/05/2024',
                            description: 'Date d\'entrée en cellule de maison (format jj/mm/aaaa)'
                        ),
                        new OA\Property(property: 'observations', type: 'string', nullable: true, example: 'Inscrite après la croisade')
                    ]
                )
            )

        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Membre créé avec succès',
                content: new OA\JsonContent(
                    ref: new Model(type: Member::class, groups: ['getMembers'])
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Requête invalide'
            )
        ]
    )]
    //#[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')", message: "Vous n'avez pas les droits suffisants pour supprimer ce membre.")]
    #[IsGranted(new Expression('is_granted("ROLE_ADMINISTRATEUR") or is_granted("ROLE_MANAGER")'))]
    #[Route('/api/membres', name: 'app_member_new', methods: ['POST'])]
    public function createMember(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        try {
            $jsonRecu = $request->getContent();
            $member = $serializer->deserialize($jsonRecu, Member::class, 'json');

             //  Lier le créateur connecté
            $member->setCreatedBy($this->getUser())
                    ->setCreatedAt(new \DateTimeImmutable());

            $errors = $validator->validate($member);

            if ($errors->count() > 0) {

                $error_serialize=$serializer->serialize($errors,'json');
                return new JsonResponse($error_serialize,Response::HTTP_BAD_REQUEST,[],true);
            }
        
            $entityManager->persist($member);
            $entityManager->flush();

            

            $context = SerializationContext::create()->setGroups(["getMembers"]);
            $jsonMember = $serializer->serialize($member,'json',$context);
            #$location = $urlGenerator->generate('detailMember', ['id'=>$member->getId()],UrlGeneratorInterface::A) */
            return new JsonResponse($jsonMember, Response::HTTP_CREATED,[
                'message'=>'Membre crée avec succès',
                'id'=>$member->getId()
            ],true);
        } catch (\Exception $e) {
            return $this->json([
                'error'=>'Erreur lors de la création du membre',
                'details'=>$e->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        }
       

    
        return $this->json($member, Response::HTTP_CREATED, [], ['groups'=>'getMembers']);
    }

    

    //UPDATE MEMBER
        #[OA\Put(
        path: '/api/membres/{id}',
        summary: 'Modifier les informations d’un membre',
        security: [['bearerAuth' => []]],
        tags: ['Membres'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Identifiant du membre à modifier',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'firstName', type: 'string', example: 'Sarah'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'Kouadio'),
                        new OA\Property(property: 'tel', type: 'string', example: '772233445'),
                        new OA\Property(property: 'quartier', type: 'string', example: 'FANN'),
                        new OA\Property(property: 'nationalite', type: 'string', example: 'Côte d\'Ivoire'),
                        new OA\Property(property: 'isMember', type: 'boolean', example: true),
                        new OA\Property(property: 'memberJoinedDate', type: 'string', example: '12/04/2024', description: 'Format jj/mm/aaaa'),
                        new OA\Property(property: 'isBaptized', type: 'boolean', example: true),
                        new OA\Property(property: 'baptismDate', type: 'string', example: '01/06/2024', description: 'Format jj/mm/aaaa'),
                        new OA\Property(property: 'hasTransport', type: 'boolean', example: false),
                        new OA\Property(property: 'transportDate', type: 'string', nullable: true, example: null, description: 'Format jj/mm/aaaa ou null'),
                        new OA\Property(property: 'isInHomeCell', type: 'boolean', example: true),
                        new OA\Property(property: 'homeCellJoinDate', type: 'string', example: '15/05/2024', description: 'Format jj/mm/aaaa'),
                        new OA\Property(property: 'observations', type: 'string', nullable: true, example: 'Inscrite après la croisade')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 204,
                description: 'Membre mis à jour avec succès'
            ),
            new OA\Response(
                response: 400,
                description: 'Erreur de validation ou format incorrect'
            ),
            new OA\Response(
                response: 403,
                description: 'Accès refusé – rôle insuffisant'
            ),
            new OA\Response(
                response: 404,
                description: 'Membre non trouvé'
            )
        ]
    )]
    #[IsGranted(new Expression('is_granted("ROLE_ADMINISTRATEUR") or is_granted("ROLE_MANAGER")'))]
    #[Route('/api/membres/{id}', name: 'app_member_edit', methods: ['PUT'])]
    public function updateMember(Request $request, Member $currentMember, EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        /* $updateMember= $serializer->deserialize($request->getContent(), 
                    Member::class,
                    'json', 
                    [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMember]); */
        $json = $request->getContent();
        $context = DeserializationContext::create(); 
        $context->setAttribute(ExistingObjectConstructor::ATTRIBUTE, $currentMember);
        $serializer->deserialize($json, get_class($currentMember), 'json', $context);    
        
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }



// UPDATE PROFILE
#[OA\Put(
    path: '/api/profile',
    summary: 'Modifier les informations du profil utilisateur',
    tags: ['Profil Utilisateur'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'firstName', type: 'string'),
                    new OA\Property(property: 'lastName', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'image', type: 'string', format: 'binary', nullable: true)
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Profil modifié avec succès'),
        new OA\Response(response: 400, description: 'Requête invalide'),
        new OA\Response(response: 401, description: 'Utilisateur non connecté')
    ]
)]
#[IsGranted("IS_AUTHENTICATED_FULLY")]
#[Route('/api/profile', name: 'app_profile', methods: ['PUT'])]
public function updateProfile(
    Request $request,
    ValidatorInterface $validator,
    EntityManagerInterface $em,
    SerializerInterface $serializer
): JsonResponse {
    /** @var User|null $currentUser */
    $currentUser = $this->getUser();

    if (!$currentUser instanceof User) {
        return $this->json(['error' => 'Utilisateur non connecté'], Response::HTTP_UNAUTHORIZED);
    }

    // Champs texte
    $firstName = $request->request->get('firstName');
    $lastName  = $request->request->get('lastName');
    $email     = $request->request->get('email');

    if ($firstName !== null) {
        $currentUser->setFirstName($firstName);
    }
    if ($lastName !== null) {
        $currentUser->setLastName($lastName);
    }
    if ($email !== null) {
        $currentUser->setEmail($email);
    }

    // Fichier image (optionnel)
    $file = $request->files->get('image');
    if ($file) {
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($this->getParameter('photo_profil'), $filename);
        $currentUser->setProfilePhoto($filename);
    }

    // Validation
    $errors = $validator->validate($currentUser);
    if (count($errors) > 0) {
        return new JsonResponse(
            $serializer->serialize($errors, 'json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );
    }

    $em->flush();

    return $this->json(['message' => 'Profil mis à jour avec succès'], Response::HTTP_OK);
}

    
}
