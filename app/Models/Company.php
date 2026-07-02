<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function credentials()
    {
        return $this->hasMany(HaciendaCredential::class);
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class);
    }
}
