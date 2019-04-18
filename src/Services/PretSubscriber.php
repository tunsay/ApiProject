<?php
namespace App\Services;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PretSubscriber implements EventSubscriberInterface
{
    private $token;
    public function __construct(TokenStorageInterface $token){
        $this->token=$token;
    }
    public static function getSubscribedEvents(){
        return [
            KernelEvents::VIEW=>['getAuthenticatedUser',EventPriorities::PRE_WRITE]
        ];
    }
    public function getAuthenticatedUser(GetResponseForControllerResultEvent $event){

        $entity = $event->getControllerResult(); // récupère l'entity qui a déclenché l'évemenement
        $method = $event->getRequest()->getMethod(); // récupère la méthode dans request
        $adherent = $this->token->getToken()->getUser(); // récupère l'adhérent connecté
        if($entity instanceof Pret){ //verification de la methode
            if($method == Request::METHOD_POST){
            $entity->setAdherent($adherent);//on affecte l'adherent au prêt
            }elseif($method == Request::METHOD_PUT){
                if($entity->getDateRetourReelle() == null){
                    $entity->getLivre()->getDispo(false);
                }else{
                    $entity->getLivre()->getDispo(true);
                }
            }elseif($method == Request::METHOD_DELETE){
                $entity->getLivre()->getDispo(true);
            }
        }
        return;
    }
}


?>