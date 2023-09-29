<?php
declare(strict_types=1);

namespace App\Models\SkyApp;
use App\Models\Customer\Customer;
use App\Controller\Customer\TransactionController;

class InterectiveSkyApp{
    private Customer $authCustomer;
    private TransactionController $transactionController;
    

    public function __construct(Customer $customer){
        $this->authCustomer = $customer;
        $this->transactionController = new TransactionController($this->authCustomer);
    }

    public function showTransactions(){
        printf("\nYour Transactions are-----\n");
        $transactions = $this->transactionController->getTransactions();
        foreach($transactions as $transaction){
            printf("Email - %s, %s - %.2f BDT at %s\n", 
            $this->authCustomer->getEmail(), $transaction->type, $transaction->amount, $transaction->date);
        }
        printf("\n");
    }

    public function dipositMoney(){
        $this->transactionController->diposit();
    }
    
        
}
