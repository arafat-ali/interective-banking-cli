<?php

declare(strict_types=1);

namespace App\Models\Customer;
use App\Models\Customer\Transaction;
use App\Models\Customer\Customer;
use App\Enum\TransactionTypeEnum;

class Transactions{
    private array $transactionList;

    public function __construct(array $list, $user=null)
    {
        foreach($list as $row){
            if($user!==null && strtolower((string)$row[0]) != $user->getEmail()) continue;
            $transaction = new Transaction();
            $transaction->setTransaction($user, TransactionTypeEnum::fromValue($row[1]), (float)$row[2],$row[3]);
            $this->insertTransactionToList($transaction);
        }
        
    }

    public function insertTransactionToList(Transaction $transaction){
        $this->transactionList[]= $transaction;
    }

    public function getList(){
        return $this->transactionList;
    }
}
