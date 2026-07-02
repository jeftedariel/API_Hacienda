<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyUserSession extends Model
{
    protected $table = 'company_user_sessions';

    public $timestamps = false;

    protected $guarded = [];
}
