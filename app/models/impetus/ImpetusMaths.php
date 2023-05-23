<?php 

namespace app\models\impetus;

class ImpetusMaths
{
    /**
     * Calculates the fatorial of a number
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
     * Verify if the number is prime or not
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