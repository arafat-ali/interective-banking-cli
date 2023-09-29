<?php
declare(strict_types=1);
namespace App\Models;

use App\Models\Auth\AuthApp;
use App\Models\Customer\Customer;
class App{

    private AuthApp $authApp;
    private Customer $authCustomer;

    private const SHOW_CURRENT_BALANCE = 1;
    private const SHOW_TRANSACTION = 2;
    private const DEPOSITE_MONEY = 3;
    private const WITHDRAW_MONEY = 4;
    private const TRANSFER_MONEY = 5;
    private const LOGOUT = 6;


    private array $options = [
        self::SHOW_CURRENT_BALANCE => 'Show Current Balance',
        self::SHOW_TRANSACTION => 'Show Transactions',
        self::DEPOSITE_MONEY => 'Deposit Money',
        self::WITHDRAW_MONEY => 'Withdraw Money',
        self::TRANSFER_MONEY => 'Transfer Money',
        self::LOGOUT => 'Logout',
    ];

    public function __construct(){
        $this->authApp = New AuthApp();
        //$this->skyApp = New InterectiveSkyApp(new FileStorage());
    }

    public function run(){
        $this->authApp->run();
        $this->authCustomer = $this->authApp->getAuthCustomer();
        printf("\nWellcome %s\n\n", $this->authCustomer->getName());
        
        while($this->authApp->getAuthenticationSuccess() ){
            foreach ($this->options as $option => $label) {
                printf("Press %d to - %s\n", $option, $label);
            }

            $choice = intval(readline("Enter your option: "));
            switch ($choice) {
                case self::SHOW_CURRENT_BALANCE:
                    printf("\nYour Current Balance is %.2f Taka\n\n", $this->authCustomer->getBalance());
                    break;
                case self::SHOW_TRANSACTION:
                    echo "";
                    break;
                case self::LOGOUT:
                    return;
                default:
                    echo "Invalid option.\n";
            }
            
        }
    }
}