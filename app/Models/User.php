<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLoggable, LogsActivity;

    public bool $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
    ];

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
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });

        static::saving(function ($user) {
            $user->name = ucwords(strtolower($user->name));
        });

        static::saved(function ($user) {
            if ($role = request()->role) {
                $user->syncRoles($role);
            }
        });
    }

    public function getAcronymAttribute()
    {
        $words = explode(' ', $this->name);
        $acronym = '';

        foreach ($words as $w) {
            $acronym .= substr($w, 0, 1);
        }

        return $acronym;
    }

    public function lastLogin()
    {
        return $this->hasMany(AuthenticationLog::class, 'authenticatable_id')->orderBy('login_at', 'desc')->take(5);
    }

    public function isSuperadmin()
    {
        return $this->hasRole('superadmin') ? true : false;
    }
}
