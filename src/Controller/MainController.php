<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump("submitted data");
            dump($form->getData());
            $user = new User();
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            $this->addFlash("success", "Hey Bro, welcome!");
            return $this->redirectToRoute('main');
        }
        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
