<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(\Laravel\Fortify\Contracts\LogoutResponse::class, \App\Http\Responses\CustomLoggedOutResponse::class);
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('layouts.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {

            $check = User::where('ic_no', $request->email)->first();

            if ($check) {
                // if ($check->hasRole(['admin', 'executive'])) {
                //     if (!Hash::check($request->password, $check->password)) {

                //         throw ValidationException::withMessages([
                //             'email' => ['The provided credentials are incorrect.'],
                //         ]);
                //     } else {
                //         return $check;
                //     }
                // } 
                if (Hash::check($request->password, $check->password)) {
                    return $check;
                }
                else {
                    $response = Http::post('https://myprofil.iyres.gov.my/api/login', [
                        'ic_no' => $request->email,
                        'password' => $request->password,
                    ]);

                    if ($response->successful()) {

                        $response = $response->json();

                        $user = User::where('ic_no', $request->email)->first();

                        if ($user) {

                            if ($user->status == User::REJECT || $user->status == User::DELETE || $user->status == User::PENDING) {
                                throw ValidationException::withMessages([
                                    'email' => ['Your account is not authorised.'],
                                ]);
                            } else {

                                return $user;
                            }
                        } else {

                            throw ValidationException::withMessages([
                                'email' => ['The provided credentials are incorrect.'],
                            ]);
                        }
                    }
                }
            } else {

                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        });
    }
}
