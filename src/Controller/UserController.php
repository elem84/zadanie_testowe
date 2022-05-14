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

    #[Route('/user/edit/{user}', name: 'user_edit')]
    public function edit(Request $request, User $user): Response
    {
        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/tabGeneral/{user}', name: 'user_edit_tab_general')]
    public function tabGeneral(Request $request, User $user): Response
    {
        $userDTOForm = new UserDTOForm();
        $userDTOForm->setUsername($user->getUsername());

        $form = $this->createForm(UserType::class, $userDTOForm);
        $form->handleRequest($request);

        return $this->render('user/tab-general.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/tabDevices/{user}', name: 'user_edit_tab_devices')]
    public function tabDevices(User $user): Response
    {
        return $this->render('user/tab-devices.html.twig', [
            'user' => $user,
        ]);
    }
}
