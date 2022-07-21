<?php


namespace App\Entity;


interface CustomerOwnedInterface
{
    public function getId(): ?int;
    public function getCustomer(): ?Customer;
    public function setCustomer(?Customer $customer): self;
}
