<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Contracts\Role as RoleContract;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    public $incrementing  = false;
    protected $primaryKey = 'id';
    protected $guarded    = [];
}
