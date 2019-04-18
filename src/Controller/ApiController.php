<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/Reg", name="listeRegions")
     */
    public function listeRegion(SerializerInterface $serializer)
    {
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        /**
         * on passe $mesRegions qui est un api, en Array, genre en tableau
         */
        // $mesRegionsTab=$serializer->decode($mesRegions, 'json');
        /**
         * ensuite ce tableau on le transforme en objet, il faut là avoir un entity au préalable
         */
        // $mesRegionsObjet=$serializer->denormalize($mesRegionsTab, 'App\Entity\Region[]');
        /**
         * SINON on oublie les deux ligne en haut, on met directement en objet, donc là encore l'entity requis
         */
        $mesRegions=$serializer->deserialize($mesRegions,'App\Entity\Region[]', 'json');
        // dump($mesRegionsObjet);
        // die();
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'pageCourante'=>"accueil",
            'mesRegions'=>$mesRegions
        ]);
    }

    /**
     * @Route("/Deps", name="listeDepsParRegions")
     */
    public function listeDepsParRegion(Request $request, SerializerInterface $serializer)
    {
        $codeRegion=$request->query->get('region');

        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions=$serializer->deserialize($mesRegions,'App\Entity\Region[]', 'json');
        
        if($codeRegion == null || $codeRegion == "Toutes" ) {
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/departements');
        }else{
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }
        //décodage du format json en format tabelau
        $mesDeps=$serializer->decode($mesDeps, 'json');
        
        return $this->render('api/listDep.html.twig', [
            'controller_name' => 'ApiController',
            'pageCourante'=>"accueil",
            'mesRegions'=>$mesRegions,
            'mesDeps'=>$mesDeps
        ]);
    }

    
}