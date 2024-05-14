<?php

namespace ArthurNassar\LaravelStripeConnect\Enums;

enum LinkType: string
{
    case Onboarding = 'account_onboarding';
    case Update = 'account_update';
}
