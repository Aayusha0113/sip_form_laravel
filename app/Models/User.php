<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = false;

   protected $fillable = [
    'username',
    'password',
    'role',
    'permissions',
    'created_at',
];

/**
     * This "boot" function ensures created_at is filled automatically 
     * even though $timestamps is set to false.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->created_at) {
                $model->created_at = now();
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
           'password' => 'hashed',
            'permissions' => 'array', // Added this so Laravel handles the JSON/comma list for you
        ];
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function hasPermission($permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role ?? '') === 'admin';
    }
}
