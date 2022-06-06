<?php

namespace App\Services;

use App\Models\Social\SocialLinkedinAccount;
use App\Models\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialFacebookAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialLinkedinAccount::whereProvider('linkedin')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialLinkedinAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => 'linkedin',
            ]);
            $user = User::whereEmail($providerUser->getEmail())->first();
            if (!$user) {
                $user = User::create([
                    'email'    => $providerUser->getEmail(),
                    'last_name'     => $providerUser->getName(),
                    'first_name'    => $providerUser->getNickname(),
                    'profile_picture'   => $providerUser->getAvatar() ? $providerUser->getAvatar() : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($providerUser->getEmail()))),
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
