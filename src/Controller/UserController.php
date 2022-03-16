<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\User\DTO\UserDTOForm;
use App\Form\Type\User\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/user/index', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->userRepository->findBy([], ['username' => 'ASC']),
        ]);
    }

    #[Route('/user/add', name: 'user_add')]
    public function add(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $userDTOForm = new UserDTOForm();

        $form = $this->createForm(UserType::class, $userDTOForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = new User();
            $user->setUsername($userDTOForm->getUsername());

            $hashedPassword = $passwordHasher->hashPassword($user, $userDTOForm->getPassword());
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Zapisano');

            return $this->redirectToRoute('user');
        }

        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
