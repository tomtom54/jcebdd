<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /*
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    //private $fullName = '';

    /*
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=50)
     */
    //protected $username = '';

    /*
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    //protected $email = '';

    /*
     * @ORM\Column(type="string")
     */
    //protected $password = '';

    /*
     * @ORM\Column(type="json")
     */
    //protected $roles = [];

    /*
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    //protected $salt;

    /*public function eraseCredentials()
    {
    }*/

    public function getId(): int
    {
        return $this->id;
    }

    /*public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }*/

    /*public function getUsername(): string
    {
        return $this->username;
    }*/

    /*public function setUsername(string $username): void
    {
        $this->username = $username;
    }*/

    /*public function getEmail(): string
    {
        return $this->email;
    }*/

    /*public function setEmail(string $email): void
    {
        $this->email = $email;
    }*/

    /*public function getPassword(): string
    {
        return $this->password;
    }*/

    /*public function setPassword(string $password): void
    {
        $this->password = $password;
    }*/

    /*public function getRoles(): array
    {
        $roles = $this->roles;

        // il est obligatoire d'avoir au moins un rôle si on est authentifié, par convention c'est ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }*/

    /*public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }*/

    /*public function getSalt(): ?string
    {
        return null;
    }*/

    /*public function eraseCredentials(): void
    {
    }*/

    /*public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }*/
}
