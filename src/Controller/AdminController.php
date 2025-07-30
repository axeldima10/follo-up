<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Manager;
use App\Entity\Consultant;
use App\Security\EmailVerifier;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use OpenApi\Attributes as OA;


final class AdminController extends AbstractController
{

     public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    
    
    #[OA\Post(
    path: '/api/admin/consultant',
    summary: 'Créer un consultant',
    tags: ['Utilisateurs'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['email', 'firstName', 'lastName'],
            properties: [
                new OA\Property(property: 'firstName', type: 'string', example: 'Sarah'),
                new OA\Property(property: 'lastName', type: 'string', example: 'Kouadio'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'consultant@example.com')
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Consultant créé avec succès'),
        new OA\Response(response: 400, description: 'Erreur de validation'),
        new OA\Response(response: 403, description: 'Accès interdit')
    ]
    )]

    #[IsGranted("ROLE_ADMINISTRATEUR", message:'Vous n\'avez pas les droits suffisants')]
    #[Route('/api/admin/consultant', name: 'app_consultant_admin', methods:["POST"])]
    public function createConsultant(SerializerInterface $serializer, Request $request, 
    ValidatorInterface $validator,UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $em): Response
    {
        $jsonRecu = $request->getContent();
        $consultant = $serializer->deserialize($jsonRecu,Consultant::class,'json');
       
        
        $errors = $validator->validate($consultant);
        if ($errors->count() > 0) {
            $jsonError = $serializer->serialize($errors,'json');
            return new JsonResponse($jsonError,Response::HTTP_BAD_REQUEST,[],true);
        }

        $password = "@JESUSISLORD";

        // encode the plain password
        $consultant->setPassword($userPasswordHasher->hashPassword($consultant,$password));
         $consultant->setRoles(['ROLE_CONSULTANT']);

        $em->persist($consultant);
        $em->flush();

    // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $consultant,
            (new TemplatedEmail())
                ->from(new Address('support@riseyourbusiness.fr', 'Follow-Up Mail Verification'))
                ->to((string) $consultant->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            ); 
            
        
        return $this->json([
            'message'=> 'Consultant créé avec succès. Un email de vérification a été envoyé.',
            'email' => $consultant->getEmail(),
        ],Response::HTTP_CREATED);    // do anything else you need here, like send an email

            //return $security->login($user, 'json_login', 'login');
        
        /* return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]); */
    }


//CREATE MANAGER

#[OA\Post(
    path: '/api/admin/manager',
    summary: 'Créer un manager',
    security: [['bearerAuth' => []]],
    tags: ['Utilisateurs'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['email', 'firstName', 'lastName'],
            properties: [
                new OA\Property(property: 'firstName', type: 'string', example: 'Fatou'),
                new OA\Property(property: 'lastName', type: 'string', example: 'Ndoye'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'manager@example.com')
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Manager créé avec succès'),
        new OA\Response(response: 400, description: 'Erreur de validation'),
        new OA\Response(response: 403, description: 'Accès interdit')
    ]
)]

#[IsGranted("ROLE_ADMINISTRATEUR",message:'Vous n\'avez pas les droits suffisants')]     
#[Route('/api/admin/manager', name: 'app_manager_admin', methods:["POST"])]
public function createManager(SerializerInterface $serializer, Request $request,
ValidatorInterface $validator,UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $em): Response
    {
        $jsonRecu = $request->getContent();
        $manager = $serializer->deserialize($jsonRecu,Manager::class,'json');
        
        
        $errors = $validator->validate($manager);
        if ($errors->count() > 0) {
            $jsonError = $serializer->serialize($errors,'json');
            return new JsonResponse($jsonError,Response::HTTP_BAD_REQUEST,[],true);
        }

        $password = "@JESUSISLORD";

        // encode the password
        $manager->setPassword($userPasswordHasher->hashPassword($manager,$password));
        $manager->setRoles(['ROLE_MANAGER']);

        $em->persist($manager);
        $em->flush();

    // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $manager,
            (new TemplatedEmail())
                ->from(new Address('support@riseyourbusiness.fr', 'Follow-Up Mail Verification'))
                ->to((string) $manager->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );     
        
        return $this->json([
            'message'=> 'Manager créé avec succès. Un email de vérification a été envoyé.',
            'email' => $manager->getEmail(),
        ],Response::HTTP_CREATED);
    }


//CREATE ADMIN
#[OA\Post(
    path: '/api/admin',
    summary: 'Créer un administrateur',
    security: [['bearerAuth' => []]],
    tags: ['Utilisateurs'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['email', 'firstName', 'lastName'],
            properties: [
                new OA\Property(property: 'firstName', type: 'string', example: 'Jean'),
                new OA\Property(property: 'lastName', type: 'string', example: 'Dupont'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com')
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Admin créé avec succès'),
        new OA\Response(response: 400, description: 'Erreur de validation'),
        new OA\Response(response: 403, description: 'Accès interdit')
    ]
)]

#[IsGranted("ROLE_ADMINISTRATEUR",message:'Vous n\'avez pas les droits suffisants')] 
#[Route('/api/admin', name: 'app_admin', methods:["POST"])]
public function createAdmin(Request $request, SerializerInterface $serializer, 
ValidatorInterface $validator,UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $em){ 

    $jsonRecu = $request->getContent();
    $admin = $serializer->deserialize($jsonRecu,User::class,'json');

    $errors=$validator->validate($admin);

    if ($errors->count() > 0) {
        $jsonError = $serializer->serialize($errors,'json');
        return new JsonResponse($jsonError, Response::HTTP_BAD_REQUEST,[],true);
    }

    $password = "@JESUSISLORD";

        // encode the password
        $admin->setPassword($userPasswordHasher->hashPassword($admin,$password));
        $admin->setRoles(['ROLE_ADMINISTRATEUR']);

        $em->persist($admin);
        $em->flush();

    // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $admin,
            (new TemplatedEmail())
                ->from(new Address('support@riseyourbusiness.fr', 'Follow-Up Mail Verification'))
                ->to((string) $admin->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
            );     
        
        return $this->json([
            'message'=> 'Admin créé avec succès. Un email de vérification a été envoyé.',
            'email' => $admin->getEmail(),
        ],Response::HTTP_CREATED);


}




#[Route('/verify/email', name: 'app_verify_email', methods: ['GET'])]
public function verifyEmail(Request $request,
UserRepository $userRepository): JsonResponse
{
    $id = $request->query->get('id'); // retrieve the user id from the url

    // Verify the user id exists and is not null
    if (null === $id) {
        return $this->json(['error' => 'Paramètre id manquant'], 400);
    }
    $user = $userRepository->find($id);

    // Ensure the user exists in persistence
    if (null === $user) {
        return $this->json(['error' => 'Utilisateur introuvable'], 404);
    }

    try {
        $this->emailVerifier->handleEmailConfirmation($request, $user->getId(), $user->getEmail());
    } catch (VerifyEmailExceptionInterface $e) {
        return $this->json(['error' => $e->getReason()], 400);
    }
    

    return $this->json(['message' => 'Email vérifié avec succès'], 200);
}


}
