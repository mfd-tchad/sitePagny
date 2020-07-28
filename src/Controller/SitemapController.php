<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function index(Request $request)
    {
        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();

        // On initialise un tableau pour lister les URLs
        $urls = [];

        // On ajoute les URLs "statiques"
        
        $urls[] = ['loc' => $this->generateUrl('home'), 'lastmod' => '2020-07-28', 
            'changefreq' => 'monthly', 'priority' => 0.8 ];
        $urls[] = ['loc' => $this->generateUrl('evenements'), 'changefreq' => 'weekly', 'priority' => 0.8 ];
        $urls[] = ['loc' => $this->generateUrl('associations')];
        $urls[] = ['loc' => $this->generateUrl('mairie')];
        $urls[] = ['loc' => $this->generateUrl('demarches')];
        $urls[] = ['loc' => $this->generateUrl('urbanisme')];
        $urls[] = ['loc' => $this->generateUrl('cadastre')];
        $urls[] = ['loc' => $this->generateUrl('patrimoine'), 'changefreq' => 'yearly'];
        $urls[] = ['loc' => $this->generateUrl('saintgregoire')];
        $urls[] = ['loc' => $this->generateUrl('eglise'), 'changefreq' => 'yearly', 'priority' => 0.8];
        $urls[] = ['loc' => $this->generateUrl('reserve'), 'changefreq' => 'monthly', 'priority' => 0.8];
        $urls[] = ['loc' => $this->generateUrl('historique')];
        $urls[] = ['loc' => $this->generateUrl('viepratique')];
        $urls[] = ['loc' => $this->generateUrl('scolaire')];
        $urls[] = ['loc' => $this->generateUrl('jeunesse')];
        $urls[] = ['loc' => $this->generateUrl('aines')];
        $urls[] = ['loc' => $this->generateUrl('ordures')];
        $urls[] = ['loc' => $this->generateUrl('sportsetloisirs')];
        $urls[] = ['loc' => $this->generateUrl('ileauxenfants')];
        $urls[] = ['loc' => $this->generateUrl('fetes')];
        $urls[] = ['loc' => $this->generateUrl('login')];
        $urls[] = ['loc' => $this->generateUrl('contact')];
        $urls[] = ['loc' => $this->generateUrl('actualite')];
        $urls[] = ['loc' => $this->generateUrl('naissances')];
        $urls[] = ['loc' => $this->generateUrl('mariages')];
        $urls[] = ['loc' => $this->generateUrl('deces')];
        $urls[] = ['loc' => $this->generateUrl('flashinfos'), 'changefreq' => 'weekly', 'priority' => 0.9];
        $urls[] = ['loc' => $this->generateUrl('fetesactu')];

        // Fabrication de la réponse XML
        $response = new Response(
        $this->renderView('sitemap/index.html.twig', ['urls' => $urls,
            'hostname' => $hostname]),
        200
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
        
    }
}
