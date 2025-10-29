<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionController extends Controller
{
    /**
     * Display the user's collection page
     *
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function indexAction(EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        // Get all packs grouped by cycle
        $cycles = $entityManager->getRepository('AppBundle:Cycle')->findBy([], ['position' => 'ASC']);
        
        // Get user's owned packs
        $userCollections = $entityManager->getRepository('AppBundle:UserCollection')->findByUser($user->getId());
        
        // Create a map of owned pack IDs for easy lookup
        $ownedPackIds = [];
        foreach ($userCollections as $collection) {
            $ownedPackIds[$collection->getPack()->getId()] = true;
        }

        return $this->render('Collection/index.html.twig', [
            'user' => $user,
            'cycles' => $cycles,
            'ownedPackIds' => $ownedPackIds
        ]);
    }

    /**
     * Toggle pack ownership for the user
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     *
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function togglePackAction(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $packId = $request->request->get('pack_id');

        if (!$packId) {
            return new JsonResponse(['success' => false, 'message' => 'Pack ID is required'], 400);
        }

        $pack = $entityManager->getRepository('AppBundle:Pack')->find($packId);
        if (!$pack) {
            return new JsonResponse(['success' => false, 'message' => 'Pack not found'], 404);
        }

        // Check if user already owns this pack
        $existingCollection = $entityManager->getRepository('AppBundle:UserCollection')
            ->findOneBy(['user' => $user, 'pack' => $pack]);

        if ($existingCollection) {
            // Remove from collection
            $entityManager->remove($existingCollection);
            $entityManager->flush();
            
            return new JsonResponse(['success' => true, 'owned' => false]);
        } else {
            // Add to collection
            $collection = new UserCollection();
            $collection->setUser($user);
            $collection->setPack($pack);
            
            $entityManager->persist($collection);
            $entityManager->flush();
            
            return new JsonResponse(['success' => true, 'owned' => true]);
        }
    }
}
