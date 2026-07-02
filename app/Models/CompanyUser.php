<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Sub-usuario de una empresa (ex tabla dinámica `<id>_master_users`).
 * Es Authenticatable para poder emitir tokens Sanctum bajo el guard
 * "company" (ver config/auth.php).
 */
class CompanyUser extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'company_users';

    public $timestamps = true;

    protected $guarded = [];

    protected $hidden = ['password', 'legacy_md5'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
