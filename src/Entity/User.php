<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Exception\UnexpectedValueException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @UniqueEntity("email")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user","subscription","setUser"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @Groups({"user","subscription","setUser"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @Groups({"user","subscription","setSusbcription"})
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $email;

    /**
     * @Groups({"user","setUser"})
     * @ORM\Column(type="string", length=255)
     */
    private $apiKey;

    /**
     * @Groups({"user","setUser"})
     * @Groups("user")
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @Groups({"user","setUser"})
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @Groups({"user","setUser"})
     * @Groups("user")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @Groups({"user","setUser"})
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="user")
     */
    private $card;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subscription", inversedBy="user" )
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"user","setUser"})
     */
    private $subscription;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles = [];

    public function __construct()
    {
        $this->card = new ArrayCollection();
        try {
            $this->CreatedAt = (new DateTime('now'));
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        $this->roles = array("ROLE_USER");

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }
    public function eraseCredentials(){

    }
    public function getUsername(){

    }

    public function getSalt(){

    }

    public function getPassword(){

    }

    public function getRoles(): ?array
    {
        return $array = array();
        //return $this->roles;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCard(): Collection
    {
        return $this->card;
    }

    public function addCard(Card $card): self
    {
        if (!$this->card->contains($card)) {
            $this->card[] = $card;
            $card->setUser($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->card->contains($card)) {
            $this->card->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getUser() === $this) {
                $card->setUser(null);
            }
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
