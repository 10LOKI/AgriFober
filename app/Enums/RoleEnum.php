<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TECHNICIEN = 'technicien';
    case AGRICULTEUR = 'agriculteur';
}
