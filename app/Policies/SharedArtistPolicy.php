<?php

namespace App\Policies;

use App\Models\SharedArtist;
use App\Models\User;

class SharedArtistPolicy
{
    public function viewAny(User $user): bool
    {
        $roleKey = $user->role?->role_key;

        return in_array($roleKey, ['master_admin', 'company_admin'], true);
    }

    public function view(User $user, SharedArtist $sharedArtist): bool
    {
        $roleKey = $user->role?->role_key;

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => (int) $sharedArtist->owner_company_id === (int) $user->company_id
                || (int) $sharedArtist->shared_with_company_id === (int) $user->company_id,
            default => false,
        };
    }

    public function share(User $user): bool
    {
        return $user->role?->role_key === 'company_admin' && !empty($user->company_id);
    }

    public function accept(User $user, SharedArtist $sharedArtist): bool
    {
        return $user->role?->role_key === 'company_admin'
            && (int) $sharedArtist->shared_with_company_id === (int) $user->company_id;
    }

    public function reject(User $user, SharedArtist $sharedArtist): bool
    {
        return $this->accept($user, $sharedArtist);
    }

    public function revoke(User $user, SharedArtist $sharedArtist): bool
    {
        return $user->role?->role_key === 'company_admin'
            && (int) $sharedArtist->owner_company_id === (int) $user->company_id;
    }
}
