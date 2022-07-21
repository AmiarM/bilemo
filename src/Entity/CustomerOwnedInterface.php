<?php


namespace App\Entity;


interface CustomerOwnedInterface
{
    public function getCustomer(): ?Customer;
    public function setCustomer(?Customer $customer): self;
}
