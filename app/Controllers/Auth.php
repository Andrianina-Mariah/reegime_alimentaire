<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function registerStepOne(): string
    {
        return view('auth/register_step1');
    }

    public function registerStepTwo(): string
    {
        return view('auth/register_step2');
    }

    public function login(): string
    {
        return view('auth/login');
    }
}
