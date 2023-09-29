<?php

declare(strict_types=1);
namespace App\Controller\Customer;
use App\Models\Customer\Customer;
use App\Trait\FilehandlerTrait;

class AuthController {
    use FilehandlerTrait;
    private Array $customers=[];

    private Customer $customer;

    public function __construct()
    {
        $this->customer = new Customer();
        $this->setCustomers($this->getItemsFromFile($this->customer->getFileName()));
    }


    private function setCustomers(Array $data){
        foreach($data as $row){
            if($row[0]==null)break;
            $newCustomer = new Customer();
            $newCustomer->setCustomer(
                (string)$row[0], 
                (string)$row[1], 
                (string)$row[2], 
                (float)$row[3]
            );
            array_push($this->customers, $newCustomer);
        }
    }

    public function getCustomer():Customer{
        return $this->customer;
    }


    public function Login():bool{
        $email = $this->getEmailWithValidation();
        $password = $this->getPasswordWithValidation();
        $loginStatus = false;
        foreach($this->customers as $customer){
            if($customer->getEmail()===$email){
                if($customer->getPassword() === md5($password)) $loginStatus=true;
                $this->customer = $customer;
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
        foreach($this->customers as $customer){
            if($customer->getEmail()===$email){
                $accountStatus = false;
                break;
            }
        }
        if(!$accountStatus){
            echo "Account already available with this email!";
        }

        $insertIntoFileStatus = $this->insertNewItemIntoFile($this->customer->getFileName(), [ $name, $email, md5($password), 0]);
        if($insertIntoFileStatus){
            $newCustomer = new Customer();
            $newCustomer->setCustomer($name, $email, md5($password), 0);
            array_push($this->customers, $newCustomer);
            $registerSuccess = true;
            $this->customer = $customer;
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