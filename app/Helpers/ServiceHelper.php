<?php 

namespace App\Helpers;

class ServiceHelper
{
    public static function getOnCreateValidations() {
        return [
            'name' => 'required',
        ];
    }

    public static function getOnEditValidations() {
        return [
            'name' => 'required',
        ];
    }
}