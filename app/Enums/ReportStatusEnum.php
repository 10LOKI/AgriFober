<?php

namespace App\Enums;

enum ReportStatusEnum: string
{
    case BROUILLON = 'brouillon';
    case GENERE = 'genere';
    case VALIDE = 'valide';
    case ARCHIVE = 'archive';
}
