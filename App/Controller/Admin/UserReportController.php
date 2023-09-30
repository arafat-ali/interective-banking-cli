<?php

declare(strict_types=1);
namespace App\Controller\Admin;
use App\Models\Admin\Admin;
use App\Models\Customer\Customer;
use App\Models\Customer\Transaction;
use App\Trait\FilehandlerTrait;

class UserReportController {
    use FilehandlerTrait;
    private Admin $authuser;
    private Array $customers=[];
    private Array $transactions=[];

    public function __construct(Admin $admin){
        $this->authuser = $admin;
        $this->customers = $this->getItemsFromFile((new Customer)->getFileName());
        $this->transactions = $this->getItemsFromFile((new Transaction)->getFileName());
    }

    public function getAllCustomer(){
        foreach($this->customers as $customer){
            printf("Name: %s, Email: %s, Balance: %.2f\n", $customer[0], $customer[1], $customer[3]);
        }
    }

    public function getAllUserTransactions(){
        foreach($this->transactions as $transaction){
            printf("Email - %s, %s - %.2f BDT at %s\n",$transaction[0], $transaction[1], $transaction[2], $transaction[3]);
        }
    }

    public function getTransactionsOfSpecificUser(){
        $email = $this->getEmailWithValidation();
        $customerName = null;
        foreach($this->customers as $row){
            if($row[1]===$email){
                $customerName = $row[0];
                break;
            }
        }
        if($customerName == null){
            echo "Account not found!";
        }

        $success = false;
        printf("\nAll Transaction History of - %s - are--\n\n", $customerName);
        foreach($this->transactions as $transaction){
            // printf("\n%s--%s\n", $transaction[0], $email);
            if($transaction[0]===$email){
                printf("%s, %s - %.2f BDT at %s\n",$transaction[0], $transaction[1], $transaction[2], $transaction[3]);
                $success = true;
            }
        }
        if(!$success){
            printf("\nNo Transaction History found of - %s \n\n", $customerName);
        }
    }

    private function getEmailWithValidation(){
        $inputEmail = (string) trim(readline('Please insert your email: '));
        if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) return strtolower($inputEmail);
        else {
            echo "\nInvalid Email!\n";
            $this->getEmailWithValidation();
        }

    }




}