<?php

namespace App\Controller;


use DateTime;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
//use Nelmio\ApiDocBundle\Attribute\Model as AttributeModel;
use OpenApi\Examples\Specs\UsingLinks\Annotations\Repository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[IsGranted(new Expression('is_granted("ROLE_ADMININSTRATEUR") or is_granted("ROLE_MANAGER")'))]
    #[Route('/api/membres/{id}', name: 'app_member_delete', methods: ['DELETE'])]
    public function deleteMember(Member $member, EntityManagerInterface $em)
    {
        $em->remove($member);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }




        #[OA\Post(
        path: '/api/membres',
        summary: 'Créer un nouveau membre',
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Exemple des données du membre à créer',
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'Sarah'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Kouadio'),
                    new OA\Property(property: 'tel', type: 'string', example: '772233445'),
                    new OA\Property(property: 'quartier', type: 'string', example: 'FANN'),
                    new OA\Property(property: 'nationalite', type: 'string', example: 'Côte d\'Ivoire'),
                    new OA\Property(property: 'isMember', type: 'boolean', example: true),
                    new OA\Property(property: 'memberJoinedDate', type: 'string', format: 'date', example: '12/04/2024'),
                    new OA\Property(property: 'isBaptized', type: 'boolean', example: true),
                    new OA\Property(property: 'baptismDate', type: 'string', format: 'date', example: '01/06/2024'),
                    new OA\Property(property: 'hasTransport', type: 'boolean', example: false),
                    new OA\Property(property: 'transportDate', type: 'string', format: 'date', nullable: true, example: null),
                    new OA\Property(property: 'isInHomeCell', type: 'boolean', example: true),
                    new OA\Property(property: 'homeCellJoinDate', type: 'string', format: 'date', example: '15/05/2024'),
                    new OA\Property(property: 'observations', type: 'string', nullable: true, example: 'Inscrite après la croisade')
                ]
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
        $serializer->deserialize($json, Member::class, 'json', $context);    
        
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

/* 
    #[Route('/api/stats', name: 'app_member_stats', methods: ['GET'])]
    public function stats(MemberRepository $memberRepository){
        //$total_des_membres = count($memberRepository->findAll());
        //$total_mois = $memberRepository->countMembersThisMonth();
        //$total_year = $memberRepository->countMembersThisYear();

        // Mois en cours
        // Mois courant
        $statsMonth = $memberRepository->getStats(date('Y'), date('m'));

        // Année courante
        $statsYear = $memberRepository->getStats(date('Y'), null);

        // Tout depuis le début
        $statsGlobal = $memberRepository->getStats(null, null);

        // Personnalisé (exemple : février 2024)
        $statsCustom = $memberRepository->getStats(2024, 2);

        dd($statsMonth,$statsYear,$statsGlobal,$statsCustom);
        //dd($total_des_membres,$total_mois,$total_year);
        //$now= new DateTime('NOW');
        //$currentMonth = $now->format('m');
        
       /*  $total_mois=0;
        $currentMonth=date('m');
        foreach ($memberRepository->findAll() as $member) {
            $date = $member->getCreatedAt();
            $mois= $date->format('m');
            
            if ($mois == $currentMonth) {
                $total_mois++;    
            }
       
        }
        dd($total_mois); */

    #}
    
}
