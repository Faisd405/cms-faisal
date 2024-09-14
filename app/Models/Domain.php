<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'domain',
        'whitelist_domains',
        'locale',
        'timezone',
        'currency',
    ];

    protected $casts = [
        'whitelist_domains' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'domain_users', 'domain_id', 'user_id');
    }
}
