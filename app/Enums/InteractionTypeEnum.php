<?php

namespace App\Enums;

enum InteractionTypeEnum: string
{
    case CHAT = 'chat';
    case DIAGNOSTIC = 'diagnostic';
    case RECOMMANDATION = 'recommandation';
    case ANALYSE_IMAGE = 'analyse_image';
}
