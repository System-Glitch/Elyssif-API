<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerValidationRules();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerValidationRules()
    {
        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, Auth::user()->password);
        });

        Validator::extend('min_decimal', function ($attribute, $value, $parameters, $validator) {
            return $value == 0 || $value >= $parameters[0];
        });

        Validator::extend('bitcoin_address', function ($attribute, $value, $parameters, $validator) {
            return checkBitcoinAddress($value);
        });

        Validator::extend('has_bitcoin_address', function ($attribute, $value, $parameters, $validator) {
            return !empty(request()->user()->address);
        });

        Validator::replacer('min_decimal', function($message, $attribute, $rule, $parameters) {
            return str_replace(':min', $parameters[0], $message);
        });
    }
}
