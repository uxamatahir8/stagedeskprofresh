<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    public function createForUser(
        int $userId,
        string $title,
        string $message,
        string $type = 'general',
        string $category = 'general',
        ?string $link = null,
        int $priority = 2,
        ?int $companyId = null,
        array $data = []
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'company_id' => $companyId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'category' => $category,
            'priority' => $priority,
            'link' => $link,
            'is_read' => false,
            'data' => empty($data) ? null : json_encode($data),
        ]);
    }

    public function createForUsers(
        iterable $users,
        string $title,
        string $message,
        string $type = 'general',
        string $category = 'general',
        ?string $link = null,
        int $priority = 2,
        array $data = []
    ): void {
        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }

            $this->createForUser(
                $user->id,
                $title,
                $message,
                $type,
                $category,
                $link,
                $priority,
                $user->company_id,
                $data
            );
        }
    }

    public function notifyMasterAdmins(
        string $title,
        string $message,
        string $type = 'general',
        string $category = 'general',
        ?string $link = null,
        int $priority = 3,
        array $data = []
    ): void {
        $users = User::whereHas('role', fn($q) => $q->where('role_key', 'master_admin'))->get();
        $this->createForUsers($users, $title, $message, $type, $category, $link, $priority, $data);
    }

    public function notifyCompanyAdmins(
        int $companyId,
        string $title,
        string $message,
        string $type = 'general',
        string $category = 'general',
        ?string $link = null,
        int $priority = 2,
        array $data = []
    ): void {
        $users = User::where('company_id', $companyId)
            ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
            ->get();

        $this->createForUsers($users, $title, $message, $type, $category, $link, $priority, $data);
    }

    public function notifyUserByRole(
        string $roleKey,
        string $title,
        string $message,
        string $type = 'general',
        string $category = 'general',
        ?string $link = null,
        int $priority = 2,
        array $data = []
    ): void {
        $users = User::whereHas('role', fn($q) => $q->where('role_key', $roleKey))->get();
        $this->createForUsers($users, $title, $message, $type, $category, $link, $priority, $data);
    }

    public function scopedQueryForUser(User $user)
    {
        $roleKey = optional($user->role)->role_key;

        if ($roleKey === 'master_admin') {
            return Notification::query();
        }

        if ($roleKey === 'company_admin') {
            return Notification::where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->company_id) {
                    $q->orWhere('company_id', $user->company_id);
                }
            });
        }

        return Notification::where('user_id', $user->id);
    }
}
