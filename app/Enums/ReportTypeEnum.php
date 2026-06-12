<?php

namespace App\Enums;

enum ReportTypeEnum: string
{
    case FERTILISATION = 'fertilisation';
    case DIAGNOSTIC = 'diagnostic';
    case RENDEMENT = 'rendement';
    case SOL = 'sol';
    case METEO = 'meteo';
}
