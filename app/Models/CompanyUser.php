<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_users';

    protected $guarded = [];

    protected $hidden = ['password', 'legacy_md5'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
