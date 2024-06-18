<?php

namespace App\Helper;

use App\Jobs\SendUserForgotPasswordJob;
use App\Services\City\CityService;

trait SendMailTrait
{
    public function callSendUserForgotPasswordMail(array $data): void
    {
        dispatch(new SendUserForgotPasswordJob($data));
    }
}
