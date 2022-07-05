<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $userId;

    #[ORM\Column(type: 'string', length: 255)]
    private $productsId;

  

    #[ORM\Column(type: 'string', length: 255)]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    private $adress;

    #[ORM\Column(type: 'string', length: 255)]
    private $postal;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $buyerFirstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $buyerLastname;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getProductsId(): ?string
    {
        return $this->productsId;
    }

    public function setProductsId(string $productsId): self
    {
        $this->productsId = $productsId;

        return $this;
    }


    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostal(): ?string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): self
    {
        $this->postal = $postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBuyerFirstname(): ?string
    {
        return $this->buyerFirstname;
    }

    public function setBuyerFirstname(string $buyerFirstname): self
    {
        $this->buyerFirstname = $buyerFirstname;

        return $this;
    }

    public function getBuyerLastname(): ?string
    {
        return $this->buyerLastname;
    }

    public function setBuyerLastname(string $buyerLastname): self
    {
        $this->buyerLastname = $buyerLastname;

        return $this;
    }

   
}
