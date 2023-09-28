<?php

declare(strict_types=1);
namespace App\Controller\Customer;
use App\Models\Customer\Customer;
use App\Trait\FilehandlerTrait;

class AuthController {
    use FilehandlerTrait;
    private Array $customers=[];

    private Customer $customer;

    public function __construct()
    {
        $this->customer = new Customer();
        $this->setCustomers($this->getItemsFromFile($this->customer->getFileName()));
    }


    private function setCustomers(Array $data){
        foreach($data as $row){
            $newCustomer = new Customer();
            $newCustomer->setCustomer(
                (string)$row[0], 
                (string)$row[1], 
                (string)$row[2], 
                (float)$row[3]
            );
            array_push($this->customers, $newCustomer);
        }
    }


    public function Login(){
        echo "\nPlease insert your Email";
    }


    public function register(){
        
    }
    


}