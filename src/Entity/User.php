<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const TYPE_ROLE = [
        'ROLE_USER',
        'ROLE_ADMIN',
        'ROLE_SUPADMIN'
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=30)
     * @ORM\Column(type="string", length=30)
     */
    private $Lastname;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=25)
     * @ORM\Column(type="string", length=25)
     */
    private $Firstname;
    
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=25)
     * @ORM\Column(type="string", length=25)
     */
    private $username;

    /**
     * @var string|null
     * @Assert\Length(min=8, max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string|null
     * @Assert\Email()
     * @Assert\Length(min=8, max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_verified = false;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=100)
     */
    private $reset_token = '';

    /**
     * @var json|null
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string {
    return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $date): self
    {
        $this->created_at = $date;
        return $this;
    }
    
    public function getTypeRole(String $type): ?string
    {
        return $this::TYPE_ROLE[$type];
    }
    /**
     * @see UserInterface
     */
    public function getRoles() : array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
       
        return array_unique($roles);
    }
    
    public function setRoles(array $roles) : self
    {
        foreach ($roles as $k => $v) {
            $this->roles[$k] = $this->getTypeRole($roles[$k]);
        }
        return $this;
    }
    
    // returns true is current user has ROLE_ADMIN role, false otherwise
    public function isAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role == "ROLE_ADMIN" || $role == 'ROLE_SUPADMIN') {
                return true;
            };
        }
        return false;
    }

    public function isSupAdmin(): bool
    {
        foreach ($this->roles as $role) {
            if ($role == 'ROLE_SUPADMIN') {
                return true;
            };
        }
        return false;
    }

    public function __toString(): ?string
    {
        return sprintf(
            "User: %s %s identifié par %s ayant pour rôle %s\n",
            $this->Firstname,
            $this->Lastname,
            $this->username,
            $this->roles[0]
        );
    }

    // les fonctions suivantes doivent être ajoutées, soit par l'éditeur, soit manuellement
    public function getSalt() :?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list ( 
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    
}