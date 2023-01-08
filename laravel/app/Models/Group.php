<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_removable',
        'is_member_restricted',
        'is_admin',
        'is_admin_restricted'
    ];

    public function groups_permissions()
    {
        return $this->hasMany(GroupsPermission::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 'Y');
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('is_admin', '<>', 'Y');
    }
}
