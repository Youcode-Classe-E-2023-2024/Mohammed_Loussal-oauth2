<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasRole extends Model
{
    use HasFactory;

    protected $table = 'user_has_roles';
    protected $fillable = [
        'role_id',
        'user_id'
    ];

    function role() {
        return $this->belongsTo(Role::class);
    }

    function user() {
        return $this->belongsTo(Permission::class);
    }
}
