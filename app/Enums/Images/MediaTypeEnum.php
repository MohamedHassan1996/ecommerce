<?php
namespace App\Enums\Images;

enum MediaTypeEnum: int{

case IMAGE = 0;
case VIDEO = 1;

public static function values(): array
{
    return array_column(self::cases(), 'value');
}
}
