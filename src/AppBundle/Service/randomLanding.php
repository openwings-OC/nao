<?php

namespace AppBundle\Service;

class randomLanding
{
    public function chooserNumberRandom(){
        $letter = "";
        $number = rand(1, 2);
        if($number === 1){
            $letter = "a";
        }
        else {
            $letter = "b";
        }
        $route = "app_landing-". $letter;
        return $route;
    }
}