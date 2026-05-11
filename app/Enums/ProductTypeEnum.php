<?php

namespace App\Enums;

enum ProductTypeEnum: string
{
    case ENGRAIS = 'engrais';
    case PESTICIDE = 'pesticide';
    case FONGICIDE = 'fongicide';
    case HERBICIDE = 'herbicide';
    case BIOLOGIQUE = 'biologique';
}
