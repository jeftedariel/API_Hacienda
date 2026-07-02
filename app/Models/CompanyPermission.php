<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPermission extends Model
{
    protected $table = 'company_permissions';

    public $timestamps = false;

    protected $guarded = [];
}
