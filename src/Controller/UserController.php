<?php
// src/Controller/adherentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Twig\Environment;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{page}", name="jce_user_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function index($page)
    {
        if($page < 1){
            throw $this->createnotfoundexception('page "'.$page.'"inexistante.');
        }
        
        return $this->indexAction($page);
    }

    /**
     * @Route("/view/{id}", name="jce_user_view", requirements={"id" = "\d+"})
     */
    public function view($id)
    {
          // On récupère le repository
          //$EntityManager = $this->getDoctrine()->getManager();
          
          $repository = $this->getDoctrine()
          ->getManager()
          ->getRepository(User::class)
        ;

        // On récupère l'entité correspondante à l'id $id
        $user = $repository->find($id);

        // $adherent est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $user) {
          throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        } 
      
        //echo $advert->m_getimage()->m_geturl();
        return $this->render('user/view.html.twig', array('user' => $user));
    }


     /**
      * @Route("/add", name="jce_user_add")
      */
    public function add(Request $request)
    {
        // On crée un objet Adherent
        $user = new User();

        // On crée le FormBuilder grâce au service form factory
        //$formBuilder = $this->createBuilder(AdherentType::class, $adherent);

        //Création du formulaire
        $form = $this->createForm(UserType::class, $user);


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
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré.');

            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('jce_user_view', array('id' => $user->getId()));
            }
          }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('user/add.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="jce_user_edit", requirements={"id" = "\d+"})
     */
     public function edit($id, Request $request)
     {
        //Récupération du Repository
        $repository = $this->getDoctrine()->getRepository(User::class);
      

        // On récupère l'entité correspondante à l'id $id
        $user = $repository->find($id);        


        // $adherent est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $user) {
          throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        } 

        //Création du formulaire
        $form = $this->createForm(UserType::class, $user);
          
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
            $em->persist($user);
            $em->flush();
      
            $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien modifié.');
      
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('jce_user_view', array('id' => $user->getId()));
            }
          }
      
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('user/edit.html.twig', array(
        'form' => $form->createView(),
        'id' => $id,
        ));
     }    

     /**
      * @Route("/delete/{id}", name="jce_user_delete", requirements={"id" = "\d+"})
      */
    public function delete($id)
    {
        return $this->render('user/delete.html.twig');
    }



      
      /**
      * @Route("/index", name="jce_user_indexAction")
      */
      public function indexAction($page)
      {
        // ...
        
        //Récupération du Repository
        $repository = $this->getDoctrine()->getRepository(User::class);

        //Retrouver tous les Adherents par ordre descendant d'identifiant
        $user = $repository->findAll();

        $NBUser = count($user);

        //initialisation tableau
        $listUsers = array();

        for ($i=0 ; $i < count($user) ; $i++) {
          $listUser[] = array(
            'id' => $user[$i]->getId(), 
            'nom' => $user[$i]->getNom(),
            'prenom' => $user[$i]->getPrenom(),
            'dateNaissance' => $user[$i]->getDateNaissance(),
          );
        }

      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('user/index.html.twig', array(
        'listUsers' => $listUsers,
        'NBUser' => $NBUser
      ));
     }

     
}