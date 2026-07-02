<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Usuario de plataforma (ex tabla legacy `users`). Es el "master user" del
 * facturador: su id coincide con companies.id de la empresa que posee.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'legacy_md5', 'remember_token'];

    public function company()
    {
        return $this->hasOne(Company::class, 'owner_user_id');
    }

    public function legacySessions()
    {
        return $this->hasMany(LegacySession::class);
    }

    public function storedFiles()
    {
        return $this->hasMany(StoredFile::class);
    }
}
