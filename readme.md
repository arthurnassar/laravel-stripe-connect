# Laravel Stripe Connect

This is a fork of [simonhamp/laravel-stripe-connect](https://github.com/simonhamp/laravel-stripe-connect)
by Simon Hamp ([@simonhamp](https://github.com/simonhamp)).

*This fork is meant for personal usage only*

> [!TIP]
> This package assumes that your `User` model is what will represent recipients of transfers from your platform,
> however this can be changed.

## Installation

Install via Composer:

```
composer require simonhamp/laravel-stripe-connect
```

Add your Stripe credentials in `.env`:

```
STRIPE_KEY=pk_test_XxxXXxXXX
STRIPE_SECRET=sk_test_XxxXXxXXX
```

Run migrations:

```
php artisan migrate
```

> [!IMPORTANT]
> If you intend to use a table other than your `users` table to record your recipients' Stripe account
> details, publish the migration by running `php artisan vendor:publish` and select the appropriate
> options. You can then edit the published migration in your app's `database/migrations` folder.

## Usage
Add the `Payable` trait to any model that you consider to represent your recipient.
 
```php
use SimonHamp\LaravelStripeConnect\Traits\Payable;

class User extends Model
{
    use Payable;
```

Then you can use the convenient methods available to get your recipients to set up or connect their
Stripe account to your platform.

Here's an example route that will get your user to go through the Stripe Connect onboarding flow:

```php
Route::get('/connect', function () {
    if (! auth()->user()->getStripeAccountId()) {
        auth()->user()->createStripeAccount(['type' => 'express']);
    }

    if (! auth()->user()->isStripeAccountActive()) {
        return redirect(auth()->user()->getStripeAccountLink());
    }

    return redirect('dashboard');
})->middleware(['auth']);
```

Once a user's Stripe account is all connected and active, you can start creating transfers:

```php
auth()->user()->transfer(10000, 'usd');
```

> [!NOTE]
> Stripe expects amounts in the smallest denomination for the currency (in this case, cents),
> so the above is a transfer of US$100 to the logged in user.
