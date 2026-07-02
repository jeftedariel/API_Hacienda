<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacySession extends Model
{
    protected $table = 'legacy_sessions';

    public $timestamps = false;

    protected $guarded = [];
}
