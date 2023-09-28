<?php
namespace App\Models;
use App\Models\Auth\Authentication;
class App{

    private Authentication $auth;

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
        $this->auth = New Authentication();
        //$this->skyApp = New InterectiveSkyApp(new FileStorage());
    }

    public function run(){
        $this->auth->run();
        while(true && $this->auth->choice!=0 ){
            foreach ($this->options as $option => $label) {
                printf("Press %d to - %s\n", $option, $label);
            }

            $choice = intval(readline("Enter your option: "));
            switch ($choice) {
                case self::SHOW_CURRENT_BALANCE:
                    echo "Current Balance is 500000";
                    break;
                
                case self::LOGOUT:
                    return;
                default:
                    echo "Invalid option.\n";
            }
            
        }
    }
}