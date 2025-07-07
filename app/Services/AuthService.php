<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use Auth;
use Hash;
use Request;
class AuthService
{
    public function register(array $data)
    {
        $org = Organization::where('name', $data['organization_name'])->first();
        if (!$org) {
            $org = Organization::firstOrCreate(['name' => $data['organization_name']]);
        }
        $data['organization_id'] = $org->id;
        unset($data['organization_name']);
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function attemptAuth(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return Auth::user();
        }

        return null;
    }

}