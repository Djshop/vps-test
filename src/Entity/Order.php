<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $orderName;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $buyerId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $products;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $total;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $shippingStatus;

    #[ORM\Column(type: 'string', length: 255)]
    private $productsId;

    #[ORM\Column(type: 'string', length: 255)]
    private $adress;

    #[ORM\Column(type: 'string', length: 255)]
    private $postal;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $phone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $refundId;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderName(): ?string
    {
        return $this->orderName;
    }

    public function setOrderName(?string $orderName): self
    {
        $this->orderName = $orderName;

        return $this;
    }

    public function getBuyerId(): ?int
    {
        return $this->buyerId;
    }

    public function setBuyerId(?int $buyerId): self
    {
        $this->buyerId = $buyerId;

        return $this;
    }

    public function getProducts(): ?string
    {
        return $this->products;
    }

    public function setProducts(?string $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getShippingStatus(): ?string
    {
        return $this->shippingStatus;
    }

    public function setShippingStatus(?string $shippingStatus): self
    {
        $this->shippingStatus = $shippingStatus;

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

    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    public function setRefundId(?string $refundId): self
    {
        $this->refundId = $refundId;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
}
