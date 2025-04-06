<?php
namespace App\Enums\Client;
enum IsMainClient:int{
    case ISMAIN = 1 ;
    case ISNOTMAIN =0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
