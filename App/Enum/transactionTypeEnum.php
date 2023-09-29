<?php

declare(strict_types=1);
namespace App\Enum;

enum TransactionTypeEnum
{
    case DIPOSIT;
    case WITHDRAW;
    case TRANSFER;
}