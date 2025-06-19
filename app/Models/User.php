<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'foto',
        'email',
        'type',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->type === 'admin'; // Gunakan nilai 'admin'
    }

    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ["user", "admin", "staff"][$value],
            set: fn ($value) => array_search($value, ["user", "admin", "staff"]),
        );
    }
}
