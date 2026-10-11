<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CASHIER = 'cajero';
    case INVENTORY = 'inventario';
}
