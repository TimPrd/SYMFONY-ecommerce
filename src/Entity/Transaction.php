<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *     min = 14,
     *     max = 16)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid credit number."
     * )
     */
    private $cardnumber;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(
     *     min = 3,
     *     max = 4)
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid CVV."
     * )
     */
    private $cvv;

    /**
     * @ORM\Column(type="string", length=4)
     * @Assert\Length(
     *     min = 4,
     *     max = 4)
     * @Assert\Regex("/^(0[1-9]|1[0-2]).\d/",
     * message="Enter a date in format :  monthyear ex: 1219")
     */
    private $expiration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $property;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardnumber(): ?int
    {
        return $this->cardnumber;
    }

    public function setCardnumber(int $cardnumber): self
    {
        $this->cardnumber = $cardnumber;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function setExpiration(string $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(string $property): self
    {
        $this->property = $property;

        return $this;
    }
}
