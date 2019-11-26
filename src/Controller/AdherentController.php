<?php
// src/Controller/adherentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adherent;
use App\Form\AdherentType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Twig\Environment;

/**
 * @Route("/Adherent")
 */
class AdherentController extends AbstractController
{
    /**
     * @Route("/{page}", name="jce_adherent_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function index($page)
    {
        if($page < 1){
            throw $this->createnotfoundexception('page "'.$page.'"inexistante.');
        }
        
        return $this->indexAction($page);
    }

    /**
     * @Route("/view/{id}", name="jce_adherent_view", requirements={"id" = "\d+"})
     */
    public function view($id)
    {
          // On récupère le repository
          //$EntityManager = $this->getDoctrine()->getManager();
          
          $repository = $this->getDoctrine()
          ->getManager()
          ->getRepository(Adherent::class)
        ;

        // On récupère l'entité correspondante à l'id $id
        $adherent = $repository->find($id);

        // $adherent est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $adherent) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        } 
      
        //echo $advert->m_getimage()->m_geturl();
        return $this->render('adherent/view.html.twig', array('adherent' => $adherent));
    }


     /**
      * @Route("/add", name="jce_adherent_add")
      */
    public function add(Request $request)
    {
        // On crée un objet Adherent
        $adherent = new Adherent();

        // On crée le FormBuilder grâce au service form factory
        //$formBuilder = $this->createBuilder(AdherentType::class, $adherent);

        //Création du formulaire
        $form = $this->createForm(AdherentType::class, $adherent);


          // Si la requête est en POST
          if ($request->isMethod('POST')) {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
            $form->handleRequest($request);

            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
            // On enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($adherent);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Adhérent bien enregistré.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('jce_adherent_view', array('id' => $adherent->getId()));
            }
          }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('adherent/add.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="jce_adherent_edit", requirements={"id" = "\d+"})
     */
     public function edit($id, Request $request)
     {
        //Récupération du Repository
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
      

        // On récupère l'entité correspondante à l'id $id
        $adherent = $repository->find($id);        


        // $adherent est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $adherent) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        } 

        //Création du formulaire
        $form = $this->createForm(AdherentType::class, $adherent);
          
        // Si la requête est en POST
        if ($request->isMethod('POST')) {
          // On fait le lien Requête <-> Formulaire
          // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
          $form->handleRequest($request);
      
          // On vérifie que les valeurs entrées sont correctes
          // (Nous verrons la validation des objets en détail dans le prochain chapitre)
          if ($form->isValid()) {
            // On enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            $em->persist($adherent);
            $em->flush();
      
            $request->getSession()->getFlashBag()->add('notice', 'Adhérent bien modifié.');
      
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('jce_adherent_view', array('id' => $adherent->getId()));
            }
          }
      
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('adherent/edit.html.twig', array(
        'form' => $form->createView(),
        'id' => $id,
        ));
     }    

     /**
      * @Route("/delete/{id}", name="jce_adherent_delete", requirements={"id" = "\d+"})
      */
    public function delete($id)
    {
        return $this->render('adherent/delete.html.twig');
    }

    /**
      * @Route("/hello-world", name="hello_the_world")
      */
      public function helloworld(Environment $twig)
      {
            $content = $twig->render('adherent/hello-world.html.twig');
        
            return new Response($content);
      }

      /**
      * @Route("/menu", name="menu")
      */
      public function menuAction($limit)
      {
        //Récupération du Repository
        $repository = $this->getDoctrine()->getRepository(Adherent::class);

        //Retrouver tous les Adherents par ordre descendant d'identifiant
        $adherents = $repository->findby(
          array(),
          array('id' => 'DESC')
        );
        //$adherents = $repository->findAll();

        //Compter le nombre à afficher
        if (count($adherents) < $limit) {
          $max = count($adherents);
        }
        else {
          $max = $limit;
        }
        
        //initialisation tableau
        $listAdherents = array();

        if ($max > 0) {
          //Remplis avec les $limit derniers adhérents
          $j = 0;
          //$listAdherents = array(array('id' => $adherents->getId(), 'nom' => $adherents->getNom(),'prenom' => $adherents->getPrenom()));
        
          for ($i = $max-1; $i >= 0; $i--) {   
          //array_push($listAdherents,array('id' => $adherents[$i]->getId(), 'nom' => $adherents[$i]->getNom(),'prenom' => $adherents[$i]->getPrenom()));
          $listAdherents[] = array('id' => $adherents[$i]->getId(), 'nom' => $adherents[$i]->getNom(),'prenom' => $adherents[$i]->getPrenom());
          $j++;
          }
        }

        return $this->render('adherent/menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe
        // les variables nécessaires au template !
        'listAdherents' => $listAdherents
        ));
      }

      
      /**
      * @Route("/index", name="index")
      */
      public function indexAction($page)
      {
        // ...
        
        //Récupération du Repository
        $repository = $this->getDoctrine()->getRepository(Adherent::class);

        //Retrouver tous les Adherents par ordre descendant d'identifiant
        $adherents = $repository->findby(
          array(),
          array('nom' => 'ASC'),
          array('prenom' => 'ASC')
        );

        //initialisation tableau
        $listAdherents = array();

        for ($i=0 ; $i < count($adherents) ; $i++) {
          $listAdherents[] = array(
            'id' => $adherents[$i]->getId(), 
            'nom' => $adherents[$i]->getNom(),
            'prenom' => $adherents[$i]->getPrenom(),
            'dateNaissance' => $adherents[$i]->getDateNaissance(),
          );
        }

      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('adherent/index.html.twig', array(
        'listAdherents' => $listAdherents
      ));
     }

     
}