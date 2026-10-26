<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'user_id',
        'event',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public static function record(string $event, ?User $actor, ?User $user, array $details = []): self
    {
        return self::create([
            'actor_id' => $actor?->id,
            'user_id' => $user?->id,
            'event' => $event,
            'details' => [
                'actor' => self::userSnapshot($actor),
                'user' => self::userSnapshot($user),
                ...$details,
            ],
        ]);
    }

    /**
     * @return array{id: int, name: string, email: string, role: string}|null
     */
    private static function userSnapshot(?User $user): ?array
    {
        if (! $user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
