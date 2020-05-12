<?php 
namespace App\Controller\Admin;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;

class AdminEvenementController extends AbstractController {
    /**
     * @var EvenementRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(EvenementRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.evenement.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index () : Response {
        $evenements = $this->repository->findAll();
        return $this->render('admin/evenement/index.html.twig', [
            'title' => 'Admin', 'titre' => 'Administration des événements',  'current_menu' => 'admin', 'evenements' => $evenements]);
    }

    /**
     * @Route("/admin/evenement/create", name="admin.evenement.new")
     */
    public function new(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($evenement); // création d'un nouvel evenement dans le tableau de cache de Symfony
            $this->em->flush(); // mise à jour de la base
            $this->addFlash('success', "Evenement créé avec succés");
            return $this->redirectToRoute('admin.evenement.index');  // On redirige l'utilisateur vers la liste des événements
        }
        return $this->render('admin/evenement/new.html.twig', [
            'title' => 'Creation', 'titre' => 'Création d\'un événement',  'current_menu' => 'admin', 'evenement' => $evenement, 'form' => $form->createView()  ]);
    }
    /**
     * @Route("/admin/evenement/{id}", name="admin.evenement.edit", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Evenement $evenement, Request $request)
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush(); // mise à jour de la base
            $this->addFlash('success', "Evenement modifié avec succés");
            return $this->redirectToRoute('admin.evenement.index');  // On redirige l'utilisateur vers la liste des événements
        }
        return $this->render('admin/evenement/edit.html.twig', [
        'title' => 'Edition', 'titre' => 'Edition d\'un événement',  'current_menu' => 'admin', 'evenement' => $evenement, 'form' => $form->createView()  ]);

    }

    /**
     * @Route("/admin/evenement/{id}", name="admin.evenement.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Evenement $evenement, Request $request)
    {
        // ajout d'un conrôle de tocken pour la sécurité. On le récupère dans la request
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->get('_tocken'))) {
        $this->em->remove($evenement);
        $this->em->flush();
        $this->addFlash('success', "Evenement supprimé avec succés");
        // return new Response('suppression');
        }
        return $this->redirectToRoute('admin.evenement.index');
    }

}