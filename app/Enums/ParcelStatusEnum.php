<?php

namespace App\Enums;

enum ParcelStatusEnum: string
{
    case GROW = 'grow';
    case HARVEST = 'harvest';
    case FALLOW = 'fallow';
}
