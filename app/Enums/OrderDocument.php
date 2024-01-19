<?php

namespace App\Enums;

enum OrderDocument: string
{
    case not_push = 'not_push';
    case pending = 'pending';
    case approved  = 'approved';
    case not_approved = 'not_approved';
}
