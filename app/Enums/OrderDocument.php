<?php

namespace App\Enums;

enum OrderDocument: string
{
    case not_push = 'not_push';
    case approved  = 'approved';
    case not_approved = 'not_approved';
}
