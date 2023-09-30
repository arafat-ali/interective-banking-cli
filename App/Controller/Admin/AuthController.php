<?php

declare(strict_types=1);
namespace App\Controller\Admin;
use App\Models\Admin\Admin;
use App\Trait\FilehandlerTrait;

class AuthController {
    use FilehandlerTrait;
    private Array $admins=[];

    private Admin $admin;

    public function __construct()
    {
        $this->admin = new Admin();
        $this->setCustomers($this->getItemsFromFile($this->admin->getFileName()));
    }


    private function setCustomers(Array $data){
        foreach($data as $row){
            if($row[0]==null)break;
            $admin = new Admin();
            $admin->setAdmin((string)$row[0], (string)$row[1], (string)$row[2]);
            array_push($this->admins, $admin);
        }
    }

    public function getAdmin():Admin{
        return $this->admin;
    }


    public function Login():bool{
        $email = $this->getEmailWithValidation();
        $password = $this->getPasswordWithValidation();
        $loginStatus = false;
        foreach($this->admins as $admin){
            if($admin->getEmail()===$email){
                if($admin->getPassword() === md5($password)) $loginStatus=true;
                $this->admin = $admin;
                break;
            }
        }
        if(!$loginStatus) {
            echo "\nInvalid credential!\n\n";
        }
        return $loginStatus;
    }


    public function register():bool{
        $name = (string) trim(readline('Please insert your name: '));
        $email = $this->getEmailWithValidation();
        $password = $this->getPasswordWithValidation();
        $accountStatus = true;
        $registerSuccess = false;
        foreach($this->admins as $admin){
            if($admin->getEmail()===$email){
                $accountStatus = false;
                break;
            }
        }
        if(!$accountStatus){
            echo "Account already available with this email!";
        }

        $insertIntoFileStatus = $this->insertNewItemIntoFile($this->admin->getFileName(), [$name, $email, md5($password)]);
        if($insertIntoFileStatus){
            $admin = new Admin();
            $admin->setAdmin($name, $email, md5($password));
            array_push($this->admins, $admin);
            $registerSuccess = true;
            $this->admin = $admin;
        }
        if(!$registerSuccess) echo "\nSomething happened bad!\n\n";
        else echo "\nSuccessfully Registerred\n\n";
        
        return $registerSuccess;
    }


    private function getEmailWithValidation(){
        $inputEmail = (string) trim(readline('Please insert your email: '));
        if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) return strtolower($inputEmail);
        else {
            echo "\nInvalid Email!\n";
            $this->getEmailWithValidation();
        }

    }

    private function getPasswordWithValidation(){
        $inputPassword = (string) readline('Please insert your password: ');
        if (strlen($inputPassword)>=6) return $inputPassword;
        else {
            echo "\nPassword minimum length must be 6!\n";
            $this->getPasswordWithValidation();
        }

    }

}