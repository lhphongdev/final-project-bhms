<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\House;
use App\Models\Tenant;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'tb_user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'dob',
        'gender',
        'avatar',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected function role(): Attribute{
    //     return new Attribute(
    //         get: fn($value) => ["landlords", "admin", "tenants"][$value],
    //     );
    // }

    public function houses()
    {
        return $this->hasMany(House::class, 'user_id');
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'user_id');
    }
}
