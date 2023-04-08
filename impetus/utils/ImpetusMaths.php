<?php 

namespace app\models\impetus;

class ImpetusMaths
{
    /**
     * Fatorial
     */
    static public function factorial($number)
    {
        $result = 1;
        while ($number > 1){
            $result *= $number;
            $number--;
        }
        return $result;
    }

    /**
     * Número primo
     */
    static public function isPrime($number)
    {
        if($number <= 1){
            return false;
        }
        for($i = 2; (int)($number^(1/2)+1); $i++){
            if($number % $i == 0){
                return false;
            }
        }
        return true;
    }

}