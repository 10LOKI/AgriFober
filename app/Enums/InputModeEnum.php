<?php

namespace App\Enums;

enum InputModeEnum: string
{
    case TEXT = 'text';
    case VOICE = 'voice';
    case IMAGE = 'image';
}
