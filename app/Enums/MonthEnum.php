<?php

namespace App\Enums;

enum MonthEnum: string
{
    case JANUARY = 'Januari';
    case FEBRUARY = 'Februari';
    case MARCH = 'Maret';
    case APRIL = 'April';
    case MAY = 'Mei';
    case JUNE = 'Juni';
    case JULY = 'Juli';
    case AUGUST = 'Agustus';
    case SEPTEMBER = 'September';
    case OCTOBER = 'Oktober';
    case NOVEMBER = 'November';
    case DECEMBER = 'Desember';
    case UNKNOWN = 'Bulan Tidak Dikenal';

    // Metode untuk mendapatkan nama bulan
    public function getName(): string
    {
        return $this->value;  // Langsung return value karena sudah string
    }

    public static function getByMonthNumber(int $monthNumber): self
    {
        return match ($monthNumber) {
            1 => self::JANUARY,
            2 => self::FEBRUARY,
            3 => self::MARCH,
            4 => self::APRIL,
            5 => self::MAY,
            6 => self::JUNE,
            7 => self::JULY,
            8 => self::AUGUST,
            9 => self::SEPTEMBER,
            10 => self::OCTOBER,
            11 => self::NOVEMBER,
            12 => self::DECEMBER,
            default => self::UNKNOWN,
        };
    }
}
