<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'date:Y-m-d H:i:s',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
        'deleted_at' => 'date:Y-m-d H:i:s',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function attachRoles($ids)
    {
        $existing_ids = $this->roles()->whereIn('roles.id', $ids)->pluck('roles.id');
        $this->roles()->attach(collect($ids)->diff($existing_ids));
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function fileable(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable')->select('id', 'name', 'filetype', 'size', 'url', 'fileable_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->select('roles.id', 'roles.name', 'roles.level');
    }
}
