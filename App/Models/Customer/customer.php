<?php

declare(strict_types=1);
namespace App\Models\Customer;

class Customer {
    private string $name;
    private string $email;
    private string $password;
    private float $balance;

    private string $filename = 'customers.csv';

    public function setCustomer(String $name, String $email, String $password, float $balance){
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->balance = $balance;
    }

    public function getFileName(){
        return $this->filename;
    }



}