<?php

namespace App\Services;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AuthenticationService
{
    public function registerUser(array $data): User
    {
        $user = User::create([
            'role' => $data['role'] ?? 'student',
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'country' => $data['country'] ?? 'Nigeria',
            'is_active' => true,
            'verification_token' => Str::random(60),
        ]);

        // Log audit trail
        $this->logAuditTrail('user_registration', 'User', $user->id, null, $user->toArray());

        return $user;
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            $this->logFailedAttempt($email);
            return null;
        }

        if (!$user->is_active) {
            $this->logAuditTrail('login_failed_inactive_account', 'User', $user->id, null, null, 'failed');
            return null;
        }

        $user->update([
            'last_login' => now(),
        ]);

        $this->logAuditTrail('user_login', 'User', $user->id, null, ['last_login' => now()]);

        return $user;
    }

    public function enableTwoFactor(User $user): string
    {
        $token = Str::random(6);
        $user->update([
            'two_factor_token' => Hash::make($token),
            'two_factor_enabled' => true,
        ]);

        return $token;
    }

    public function verifyTwoFactor(User $user, string $token): bool
    {
        if (!$user->two_factor_enabled) {
            return false;
        }

        return Hash::check($token, $user->two_factor_token);
    }

    public function changePassword(User $user, string $oldPassword, string $newPassword): bool
    {
        if (!Hash::check($oldPassword, $user->password)) {
            return false;
        }

        $oldData = $user->toArray();
        $user->update(['password' => Hash::make($newPassword)]);

        $this->logAuditTrail('password_change', 'User', $user->id, $oldData, ['password' => 'hashed']);

        return true;
    }

    public function resetPassword(User $user, string $newPassword): void
    {
        $oldData = $user->toArray();
        $user->update([
            'password' => Hash::make($newPassword),
            'verification_token' => null,
        ]);

        $this->logAuditTrail('password_reset', 'User', $user->id, $oldData, ['password' => 'hashed']);
    }

    private function logFailedAttempt(string $email): void
    {
        AuditLog::create([
            'action' => 'login_failed',
            'model' => 'User',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'failed',
            'remarks' => "Failed login attempt for email: {$email}",
        ]);
    }

    private function logAuditTrail(string $action, string $model, ?int $modelId, ?array $oldValues, ?array $newValues, string $status = 'success'): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
        ]);
    }
}
