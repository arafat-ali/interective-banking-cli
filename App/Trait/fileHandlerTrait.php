<?php
declare(strict_types=1);
namespace App\Trait;
trait FilehandlerTrait {

    public function getItemsFromFile($fileName){
        $filePath= 'App/Database/'.$fileName;
        $headingIndex = true;
        $fileData = [];
        if (($open = fopen($filePath, 'r')) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if($headingIndex==true){
                    $headingIndex = false;
                    continue;
                }
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
        $headingIndex = true;
        $fileData = [];
        if (($open = fopen($filePath, 'rw')) !== FALSE) {
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                if($headingIndex==true){
                    $headingIndex = false;
                    continue;
                }
                if($data[1]===$email){
                    $data[3] = $amount;
                }
            }
            fclose($open);
        }
        return $fileData;
    }

}