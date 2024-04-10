<?php

namespace App\Controller;

use App\Entity\Listing;
use App\Form\ListingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_listing_')]
class ListingController extends AbstractController
{
    #[Route('/', methods: ['GET','POST'])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        // List
        $listings = $entityManager->getRepository(Listing::class)->findAll();

        if (!$listings) {
            throw $this->createNotFoundException('No listings found.');
        }

        // Create
        $listing = new Listing();
        $form = $this->createForm(ListingType::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();
            $listing->setName($name);
            $entityManager->persist($listing);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('app_listing_app_listing_index');
        }

        return $this->render('listing/index.html.twig', [
            'listings' => $listings,
            'form' => $form
        ]);
    }

    // delete
    public function delete(Request $request, Listing $listing): Response
    {
        if ($this->isCsrfTokenValid('delete' . $listing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($listing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_listing_app_listing_index');
    }

}


