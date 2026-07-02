<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HaciendaCredential extends Model
{
    protected $table = 'hacienda_credentials';

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['password', 'pin'];

    /**
     * El PIN del .p12 y la contraseña ATV se guardan cifrados con la APP_KEY
     * (el legacy los guardaba en claro en el EAV de la empresa).
     */
    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'pin' => 'encrypted',
        ];
    }
}
