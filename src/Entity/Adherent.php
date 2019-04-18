<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdherentRepository")
 * @ApiResource(
 *  normalizationContext={"groups" ={"get_role_adherent"}},
 *  collectionOperations={
 *      "get" ={
 *          "method" = "GET",
 *          "path" = "/adherents",
 *          "normalization_context" = {
 *              "groups" = "get_role_adherent"
 *          }
 *      },
 *      "post" = {
 *          "method" = "POST",
 *          "path" = "/adherents/{id}",
 *          "access_control" = "is_granted('ROLE_MANAGER')",
 *          "access_control_message" = "Vous n'avez pas les droits d'accéder à cette ressource",
 *          "denormalization_context" = {
 *              "groups" = "post_role_adherent"
 *          }
 *      },
 *      "statNbPretsParAdherent"={
 *          "method" = "GET",
 *          "route_name" = "adherents_nbPrets",
 *          "controller"  = StatsController::class
 *       }
 *  },
 *  itemOperations={
 *      "get" = {
 *          "method" = "GET",
 *          "path" = "/adherents/{id}",
 *          "access_control" = "(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *          "access_control_message" = "Vous n'avez pas les droits d'accéder à cette ressource",
 *          "normalization_context" = {
 *              "groups" = "get_role_adherent"
 *          }
 *      },
 *      "put" = {
 *          "method" = "PUT",
 *          "path" = "/adherents/{id}",
 *          "access_control" = "(is_granted('ROLE_MANAGER') or is_granted('ROLE_ADHERENT') and object == user)",
 *          "access_control_message" = "Vous n'avez pas les droits d'accéder à cette ressource",
 *          "denormalization_context" = {
 *              "groups" = "post_role_manager"
 *          },
 *          "normalization_context"={
 *              "groups"={"get_role_adherent"}
 *      }
 *     }
 * }
 * )
 * 
 * @UniqueEntity(
 *      fields={"mail"},
 * message="il existe déja un genre avec le mail {{ value }}, veuillez saisir un autre mail"
 * )
 * 
 * @ApiFilter(
 *      SearchFilter::class,
 *      properties={
 *          "mail": "exact"
 *       }
 * )
 */
class Adherent implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADHERENT = 'ROLE_ADHERENT';
    const DEFAULT_ROLE = 'ROLE_ADHERENT';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="array", length=255, nullable=true)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pret", mappedBy="adherent")
     * @ApiSubresource
     * 
     */
    private $prets;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $leRole[]=self::DEFAULT_ROLE;
        $this->roles=$leRole;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->codeCommune;
    }

    public function setCodeCommune(?string $codeCommune): self
    {
        $this->codeCommune = $codeCommune;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setAdherent($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->contains($pret)) {
            $this->prets->removeElement($pret);
            // set the owning side to null (unless already changed)
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }
        return $this;
    }


    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * 
     */
    public function getRoles() 
    {
        return $this->roles;
    }

    /**
     * affecte mes roles de l'utlisateur
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles) :self
    {
        $this->roles=$roles;
        return $this;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(){
        return $this->getMail();
    }

    public function eraseCredentials(){}

}
