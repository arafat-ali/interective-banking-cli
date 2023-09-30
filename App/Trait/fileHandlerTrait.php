<?php
declare(strict_types=1);
namespace App\Trait;
trait FilehandlerTrait {

    public function getItemsFromFile($fileName){
        $filePath= 'App/Database/'.$fileName;
        $fileData = [];
        if (($open = fopen($filePath, 'r')) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                array_push($fileData, $data);
            }
            fclose($open);
        }
        return $fileData;
    }


    public function insertNewItemIntoFile($fileName, $newItem) : bool{
        $filePath= 'App/Database/'.$fileName;
        if (($open = fopen($filePath, 'a')) !== FALSE) {
            fputcsv($open, $newItem);
            fclose($open);
            return true;
        }
        return false;
    }

    public function updateBalanceIntoFile($fileName, $email, $amount){
        $filePath= 'App/Database/'.$fileName;
        $fileData = [];
        if (($open = fopen($filePath, 'r')) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if($data[1]===$email){
                    $data[3] = $amount;
                }
                array_push($fileData, $data);
            }
            fclose($open);
        }

        if (($open = fopen($filePath, 'w')) !== FALSE) {
            foreach($fileData as $data){
                fputcsv($open, $data);
            }
            fclose($open);
        }
        return $fileData;
    }

}