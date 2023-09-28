<?php

declare(strict_types=1);
namespace App\Models\Auth;

class Authentication{

    private const LOGIN = 1;
    private const REGISTER = 2;
    private const EXIT = 0;

    public bool $loginSuccess = false;
    public int $choice;


    private array $options = [
        self::LOGIN => 'Login',
        self::REGISTER => 'Register',
        self::EXIT => 'Exit System',
    ];

    public function __construct(){
        //$this->skyApp = New InterectiveSkyApp(new FileStorage());
    }

    public function run(){
        echo "\nWellcome to Interective Sky Banking App!!\n\n";
        while(true){
            foreach ($this->options as $option => $label) {
                printf("Press %d to - %s\n", $option, $label);
            }

            $this->choice = intval(readline("Enter your option: "));
            switch ($this->choice) {
                case self::LOGIN:
                    echo "";
                    break;
                
                case self::REGISTER:
                    return;
                
                case self::EXIT:
                    return;

                default:
                    echo "Invalid option.\n";
            }
            
        }
    }
}
