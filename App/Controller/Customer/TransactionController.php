<?php

declare(strict_types=1);
namespace App\Controller\Customer;
use App\Trait\FilehandlerTrait;
use App\Models\Customer\Customer;
use App\Models\Customer\Transaction;
use App\Enum\TransactionTypeEnum;
use Carbon\Carbon;

class TransactionController{
    use FilehandlerTrait;
    private array $transactions = [];
    private Customer $customer;

    public function __construct(Customer $customer){
        $this->customer = $customer;
        $this->setTransactions($this->getItemsFromFile((new Transaction)->getFileName()));
    }

    public function getTransactions(){
        return $this->transactions;
    }

    public function diposit(){
        $depositSuccess = false;
        $transaction = new Transaction;
        $type = TransactionTypeEnum::DIPOSIT;
        $amount = intval(readline('Please insert amount in BDT: '));
        $date = Carbon::now()->toDateTimeString();

        $insertIntoFileStatus = $this->insertNewItemIntoFile(
            $transaction->getFileName(), [$this->customer->getEmail(), $type, $amount, $date]
        );

        if($insertIntoFileStatus){
            $transaction->setTransaction($this->customer, $type, $amount, $date);
            array_push($this->transactions, $transaction);
            $this->balanceUpdate($this->customer->getEmail(), $this->customer->getBalance() + (float) $amount);
            $depositSuccess=true;
        }

        if(!$depositSuccess) echo "\nSomething happened bad!\n\n";
        else echo "\nSuccessfully Deposited\n\n";

    }

    public function withdraw(){
        $withdrawSuccess = false;
        $transaction = new Transaction;
        $type = TransactionTypeEnum::WITHDRAW;
        $amount = intval(readline('Please insert amount in BDT: '));
        $date = Carbon::now()->toDateTimeString();

        $insertIntoFileStatus = $this->insertNewItemIntoFile(
            $transaction->getFileName(), [$this->customer->getEmail(), $type, $amount, $date]
        );

        if($insertIntoFileStatus){
            $transaction->setTransaction($this->customer, $type, $amount, $date);
            array_push($this->transactions, $transaction);
            $this->balanceUpdate($this->customer->getEmail(), $this->customer->getBalance() - (float) $amount);
            $withdrawSuccess=true;
        }

        if(!$withdrawSuccess) echo "\nSomething happened bad!\n\n";
        else echo "\nSuccessful Withdraw\n\n";
    }

    

    

    public function transfer(){
        $email = $this->getEmailWithValidation();
        if(!$this->checkIfAccountExist($email)){
            printf("\nAccount with this email - %s not exists!\n", $email);
            printf("\nTransaction has failed\n");
        }

        if($email === $this->customer->getEmail()){
            printf("\nAccount with same account not possible!\n");
        }

        $amount = intval(readline('Please insert amount in BDT: '));
        //Transfer Operation
        $transferSuccess = false;
        $withdraw = new Transaction;
        $diposit = new Transaction;
        $date = Carbon::now()->toDateTimeString();

        $withdrawStatus = $this->insertNewItemIntoFile(
            $withdraw->getFileName(), [$this->customer->getEmail(), TransactionTypeEnum::WITHDRAW, $amount, $date]
        );
        $dipositStatus = $this->insertNewItemIntoFile(
            $diposit->getFileName(), [$email, TransactionTypeEnum::DIPOSIT, $amount, $date]
        );

        if($withdrawStatus && $dipositStatus){
            //Withdraw Operation
            $withdraw->setTransaction($this->customer, TransactionTypeEnum::WITHDRAW, $amount, $date);
            array_push($this->transactions, $withdraw);
            $this->balanceUpdate($this->customer->getEmail(), $this->customer->getBalance() - (float) $amount);

            //Diposit Operation
            $this->balanceUpdate($email, $this->customer->getBalance() - (float) $amount);
            $transferSuccess=true;
        }

        if(!$transferSuccess) echo "\nSomething happened bad!\n\n";
        else echo "\nSuccessfully Transferred\n\n";
    }

    private function balanceUpdate(String $email, float $updatedAmount){
        $this->updateBalanceIntoFile($this->customer->getFileName(), $email, $updatedAmount);

    }

    private function setTransactions(Array $data){
        foreach($data as $row){
            if(strtolower((string)$row[0]) != $this->customer->getEmail()) continue;
            $transaction = new Transaction();
            $transaction->setTransaction($this->customer, TransactionTypeEnum::fromValue($row[1]), (float)$row[2],$row[3]);
            array_push($this->transactions, $transaction);
        }
    }

    private function getEmailWithValidation(){
        $inputEmail = (string) trim(readline('Please insert your email: '));
        if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) return strtolower($inputEmail);
        else {
            echo "\nInvalid Email!\n";
            return $this->getEmailWithValidation();
        }
    }


    private function checkIfAccountExist(String $email){
        $customers = $this->getItemsFromFile($this->customer->getFileName());
        $accountFoundStatus = false;
        foreach($customers as $row){
            if($row[1] === $email){
                $accountFoundStatus = true;
                break;
            }
        }
        return $accountFoundStatus;
    }


}