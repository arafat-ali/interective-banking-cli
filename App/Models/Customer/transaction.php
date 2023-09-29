<?php

declare(strict_types=1);
namespace App\Models\Customer;
use App\Models\Customer\Customer;
use App\Enum\TransactionTypeEnum;

class Transaction{
    public Customer $customer;
    public string $type;
    public float $amount;
    public string $date;
    private string $filename = 'transactions.csv';


    public function setTransaction(Customer $customer, string $type, float $amount, string $date){
        $this->customer = $customer;
        $this->type = $type;
        $this->amount = $amount;
        $this->date = $date;
    }


    public function getFileName(){
        return $this->filename;
    }

}