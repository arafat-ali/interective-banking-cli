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
        $type = 'DIPOSIT';
        $amount = intval(readline('Please insert amount in BDT: '));
        $date = Carbon::now()->toDateTimeString();

        $insertIntoFileStatus = $this->insertNewItemIntoFile(
            $transaction->getFileName(), [$this->customer->getEmail(), $type, $amount, $date]
        );

        if($insertIntoFileStatus){
            $transaction->setTransaction( $this->customer, $type, $amount, $date);
            array_push($this->transactions, $transaction);
            //$this->customer->setBalance($this->customer->getBalance() + (float) $amount);

            $this->balanceUpdate($this->customer->getBalance() + (float) $amount);

            $depositSuccess=true;
        }

        if(!$depositSuccess) echo "\nSomething happened bad!\n\n";
        else echo "\nSuccessfully Deposited\n\n";

    }

    private function balanceUpdate(float $updatedAmount){
        $this->updateBalanceIntoFile($this->customer->getFileName(), $this->customer->getEmail(), $updatedAmount);

    }

    public function withdraw(){
        
    }

    public function transfer(){
        
    }

    private function setTransactions(Array $data){
        foreach($data as $row){
            if(strtolower((string)$row[0]) != $this->customer->getEmail()) continue;
            $transaction = new Transaction();
            $transaction->setTransaction($this->customer, $row[1], (float)$row[2],$row[3]);
            array_push($this->transactions, $transaction);
        }
    }



}