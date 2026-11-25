<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->default(true);
    }
}

