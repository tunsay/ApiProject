<?php

namespace App\Controller;

use App\Repository\AdherentRepository;
use App\Repository\LivreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    /**
     * 
     * renvoi le nb de prets par adherents
     * @Route(
     *  path = "apiPlatform/adherents/nbPretsParAdherent",
     *  name = "adherents_nbPrets",
     *  methods = {"GET"} 
     * )
     */
    public function nombrePretsParAdherent(AdherentRepository $repo)
    {
        $nbPretsParAdherent = $repo->nbPretsParAdherent();
        return $this->json($nbPretsParAdherent);
    }

    /**
     * 
     * renvoi le nb de prets par adherents
     * @Route(
     *  path = "apiPlatform/livres/meilleurslivres",
     *  name = "meilleurslivres",
     *  methods = {"GET"} 
     * )
     */
    public function meilleursLivres(LivreRepository $repo){
        $meilleursLivres = $repo->TrouveMeilleursLivres();
        return $this->json($meilleursLivres);
    }
}
