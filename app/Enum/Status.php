<?php

namespace App\Enum;

enum Status
{
    case Pending;
    case Signed;
    case PartiallyPaid;
    case Paid;
    case Cancelled;
}
