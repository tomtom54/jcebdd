<?php
// src/Controller/adherentController.php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

/*
 * @Route("/")
 */
class GeneralController extends AbstractController
{
    /**
     * @Route("/accueil", name="admin_homepage")
     */
    public function homepage()
    {

        return $this->render('layout.html.twig');
    }
}