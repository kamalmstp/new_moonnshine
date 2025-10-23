<?php

namespace App\Enums;

enum StatusEnum: string
{
    case PENDING = 'Pending';
    case SELESAI = 'Selesai';
    case DITOLAK = 'Ditolak';

    public function getColor(): ?string
    {
        return match ($this) {
            self::PENDING => 'gray',     
            self::SELESAI => 'green',
            self::DITOLAK => 'red', 
        };
    }

    public static function getColorByValue(string $value): ?string
    {
        return self::tryFrom($value)?->getColor();
    }
}