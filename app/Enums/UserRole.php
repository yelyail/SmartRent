<?php

namespace App\Enums;

enum UserRole:string
{
    case ADMIN = 'admin';
    case LANDLORD = 'landlord';
    case TENANTS = 'tenants';
    case STAFF = 'staff';
    case GUEST = 'guest';
}
