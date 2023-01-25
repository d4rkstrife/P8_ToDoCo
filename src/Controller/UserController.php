<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class UserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $em)
    {

    }

    #[Route('/users', name: 'user_list')]
    public function listAction(): Response
    {
        if(!$this->isGranted(UserVoter::VIEW)){
            return $this->redirectToRoute('app_login');
        };
        return $this->render('user/list.html.twig', ['users' => $this->userRepository->findAll()]);
    }


    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setUuid(Uuid::v4());
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{uuid}/roleChange', name: 'user_roleChange')]
    public function roleChange(User $user): Response
    {
        if(!$this->isGranted(UserVoter::EDIT, $user)){
            return $this->redirectToRoute('app_login');
        }
        if($user->getRoles()==['ROLE_USER']){
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirectToRoute('user_list');
    }
}
