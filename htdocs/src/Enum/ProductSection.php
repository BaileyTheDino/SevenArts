<?php

declare(strict_types=1);

namespace App\Enum;

enum ProductSection: string
{
    case SoftDrinks = 'Softdrinks';
    case Snacks = 'Snack';
    case Beer = 'Beer';
    case Wine = 'Wine';
    case Alcoholic = 'Alcoholic';
    case Other = 'Other';
}
