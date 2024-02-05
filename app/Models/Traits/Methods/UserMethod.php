<?php

namespace App\Models\Traits\Methods;

use App\Domains\Email\Models\Email;
use App\Domains\Frontend\Order\Models\Traits\Enum\OrderEnum;
use App\Domains\Organizer\Role\Models\Roles;
use App\Domains\User\Models\User;
use App\Domains\User\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

/**
 * Trait UserMethod.
 */
trait UserMethod
{
    public function isActive(): bool
    {
        return $this->status;
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isSocial(): bool
    {
        return $this->provider && $this->provider_id;
    }

    public function createAndAssignRole()
    {
        $permissions = Permission::all()->pluck('id');
        foreach (['owner', 'admin'] as $role) {
            $role = Roles::create([
                'user_id' => $this->id,
                'name' => Str::slug($role, '_') . '_' . $this->id,
                'original_name' => $role,
                'user_type' => 'User',
                'guard_name' => 'api',
            ]);
            $role->syncPermissions($permissions);
        }
        $this->assignRole(['buyer', 'admin' . '_' . $this->id, 'owner' . '_' . $this->id]);
    }

    public function isAdmin(): bool
    {
        return Auth::guard('admin')->user() != null && Auth::guard('admin')->user()?->hasRole('admin');
    }

    public function sendPasswordResetNotification($token): void
    {
        $email = $this->email;
        $user = $this->getUserByEmail($email);
        $userName = $user ? $user->first_name . ' ' . $user->last_name : $this->email;

        Email::sendEmail('users.reset_password', ['[Email]' => $userName, '[Activation Link]' => env('BASE_FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . $email], $email);
    }

    public function getUserByEmail($email)
    {
        return User::whereEmail($email)->first();
    }

    public function addUserMedia()
    {
        $this->clearMediaCollection('user_images');

        return $this->addMediaFromRequest('image')->toMediaCollection('user_images', 'user');
    }

    public function fetchOriginalImage()
    {
        return $this->getFirstMediaUrl('user_images');
    }

    public function fetchThumbnailImage()
    {
        return $this->getFirstMediaUrl('user_images', 'thumb');
    }

    public function generateToken()
    {
        $this->email_token = encrypt($this->id . '|' . substr(Str::random(16), 0, 16));
        $this->expires_at = Carbon::now()->addMinutes(15);

        $this->save();

        return $this;
    }

    public function sendVerifyEmailNotification($token, $username = null): void
    {
        $url = env('BASE_FRONTEND_URL') . '/verifyemail?token=' . $token;
        $this->notify(new VerifyEmailNotification($url, $username));
    }

    public function reSendVerifyEmailNotification($token, $email, $fullName = null): void
    {
        $URL = env('BASE_FRONTEND_URL') . '/verifyemail?token=' . $token;
        Email::sendEmail('users.verify_user', [
            '[Full Name]' => $fullName,
            '[Activation Link]' => $URL,
            '[Email]' => $email,
            '[Privacy Policy Link]' => env('BASE_FRONTEND_URL') . '/' . OrderEnum::PRIVACY_POLICY_LINK,
            '[Instagram Link]' => OrderEnum::INSTAGRAM_LINK,
            '[Facebook Link]' => OrderEnum::FACEBOOK_LINK,
            '[LINKEDIN Link]' => OrderEnum::LINKEDIN_LINK
        ], $email);
    }

    public function tokenIsValid()
    {
        return $this->id && $this->expires_at && Carbon::now()->lt($this->expires_at);
    }

    public function sendThankYouNotification($token): void
    {
        $email = $this->email;
        $user = $this->getUserByEmail($email);
        $userName = $user ? $user->first_name . ' ' . $user->last_name : $this->email;

        Email::sendEmail('users.thankyou', ['[Email]' => $userName], $email);
    }
}
