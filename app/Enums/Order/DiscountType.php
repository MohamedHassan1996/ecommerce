<?php
namespace App\Enums\Order;
enum DiscountType:int{
    case PERCENTAGE = 1 ;
    case FIXCED = 0;
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
