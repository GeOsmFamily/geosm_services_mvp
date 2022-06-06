<?php

namespace App\Services;

use App\Models\Social\SocialOsmAccount;
use App\Models\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialOsmAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialOsmAccount::whereProvider('openstreetmap')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialOsmAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => 'openstreetmap',
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email'    => $providerUser->getEmail(),
                    'last_name'     => $providerUser->getName(),
                    'profile_picture'   => $providerUser->getAvatar() ? $providerUser->getAvatar() : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($providerUser->getName()))) . '?d=mm&s=300',
                    'token' => $providerUser->token,
                    'password' => md5(rand(1, 10000))
                ]);
                $user->assignRole('user');
                $user->markEmailAsVerified();
            }
            $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
}
