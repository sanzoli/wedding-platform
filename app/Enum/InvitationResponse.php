<?php

namespace App\Enum;

enum InvitationResponse: string
{
    case Yes = 'yes';
    case ProbablyYes = 'probably_yes';
    case ProbablyNo = 'probably_no';
    case No = 'no';
}
