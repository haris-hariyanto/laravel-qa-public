<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'group_id',
        'email_verified_at',
        'avatar',
        'avatar_path',
        'avatar_tem',
        'avatar_tem_path',
        'avatar_tem_expired',
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

    public function sendPasswordResetNotification($token)
    {
        $resetPasswordURL = route('password.reset', ['token' => $token, 'email' => $this->email]);

        $this->notify(new ResetPassword($resetPasswordURL, $this->username));
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function avatar()
    {
        return $this->avatar ? $this->avatar : 'https://ui-avatars.com/api/?name=' . $this->username . '&size=128&length=1';
    }
}
