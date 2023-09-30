<?php
declare(strict_types=1);
namespace App\Models;

use App\Models\Auth\AuthAdminApp;
use App\Models\Admin\Admin;
use App\Models\SkyApp\InterectiveSkyAdminApp;
class AdminApp{

    private AuthAdminApp $authAdminApp;
    private Admin $admin;

    private const SHOW_ALL_CUSTOMER = 1;
    private const SHOW_All_TRANSACTION = 2;
    private const SHOW_SPECIFIC_USER_TRANSACTION = 3;
    private const LOGOUT = 4;


    private array $options = [
        self::SHOW_ALL_CUSTOMER => 'Show All Customer',
        self::SHOW_All_TRANSACTION => 'Show All Transactions',
        self::SHOW_SPECIFIC_USER_TRANSACTION => 'Show Specific User Transaction',
        self::LOGOUT => 'Logout',
    ];

    public function __construct(){
        $this->authAdminApp = New AuthAdminApp();
    }

    public function run(){
        $this->authAdminApp->run();
        $this->admin = $this->authAdminApp->getAuthAdmin();
        printf("\nWellcome %s\n\n", $this->admin->getName());
        
        while($this->authAdminApp->getAuthenticationSuccess()){
            foreach ($this->options as $option => $label) {
                printf("Press %d to - %s\n", $option, $label);
            }

            $choice = intval(readline("Enter your option: "));
            switch ($choice) {
                case self::SHOW_ALL_CUSTOMER:
                    (new InterectiveSkyAdminApp($this->admin))->showAllUser();
                    break;

                case self::SHOW_All_TRANSACTION:
                    (new InterectiveSkyAdminApp($this->admin))->showTransactionsOfAllUser();
                    break;

                case self::SHOW_SPECIFIC_USER_TRANSACTION:
                    (new InterectiveSkyAdminApp($this->admin))->showTransactionsOfSpecificUser();
                    break;

                case self::LOGOUT:
                    $this->authAdminApp->logoutAuthCustomer();
                    $this->run();
                    break;
                default:
                    echo "Invalid option.\n";
            }
            
        }
    }
}