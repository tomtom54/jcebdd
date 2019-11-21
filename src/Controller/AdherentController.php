<?php
// src/Controller/adherentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Adherent;
use App\Form\AdherentType;
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
        //Récupération de la date
        //$dt = new \DateTime();//date("Y-m-d H:i:s");
    
        // Création de l'entité
        $adherent = new Adherent();
        $adherent->setPrenom('Anthony');
        $adherent->setNom('Burton');
        $dateNaissance = $adherent->getDateNaissance();
        $dateNaissance->setDate(1985,10,2);


        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($adherent);

        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();

        //Création du formulaire
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
        
        // Reste de la méthode qu'on avait déjà écrit
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Adhérent bien enregistré.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('jce_adherent_view', array('id' => $adherent->getId()));
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('adherent/add.html.twig', array(
          'form' => $form->createView(),
        ));
      
      
        /*     if($request->ismethod('POST')) {
                $this->addflash('notice', 'Annonce bien enregistrée.');

                return $this->redirectToRoute('jce_advert_view',['id' => 5]);
                }   

        return $this->render('Advert/add.html.twig');*/
    }

    /**
     * @Route("/edit/{id}", name="jce_adherent_edit", requirements={"id" = "\d+"})
     */
     public function edit($id, Request $request)
     {
        if($request->ismethod('POST')) {
            $this->addflash('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('jce_advert_view',['id' => $id]);
        }

        return $this->render('adherent/edit.html.twig',['id' => $id]);
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
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdherents = array(
        array('id' => 1, 'nom' => 'Bur','prenom' => 'Antho'),
        array('id' => 2, 'nom' => 'Clette','prenom' => 'Lara'),
        array('id' => 3, 'nom' => 'Pancuir','prenom' => 'Slip')
        );

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

        // Notre liste d'annonce en dur
        $listAdherents = array(
        array(
          'nom'   => 'Bur',
          'id'      => 1,
          'prenom'  => 'Antho',
          'dateNaissance' => new \Datetime()),
        array(
          'nom'   => 'Clette',
          'id'      => 2,
          'prenom'  => 'Lara',
          'dateNaissance' => new \Datetime()),
        array(
          'nom'   => 'Pancuir',
          'id'      => 3,
          'prenom'  => 'Slip',
          'dateNaissance' => new \Datetime()),
      );

      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('adherent/index.html.twig', array(
        'listAdherents' => $listAdherents
      ));
     }

     
}