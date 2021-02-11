<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_code',
        'email',
        'password',
        'position',
        'phone',
        'about',
        'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'name_code',
        'subscribe',
        'password',
        'remember_token',
        'rank',
        'email_verified_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Password Reset notification
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * E-mail verify notification
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function isDev()
    {
        return intval($this->rank) === config('user.rank.dev');
    }

    public function isAdmin()
    {
        return intval($this->rank) === config('user.rank.admin');
    }

    public function markAsAuthorized()
    {
        $info = Authorize::getAuthorizeInformation();
        Authorize::create([
            'ip_address' => $info['ip_address'],
            'platform' => $info['platform'],
            'platform_version' => $info['platform_version'],
            'browser' => $info['browser'],
            'browser_version' => $info['browser_version'],
            'device' => $info['device'],
            'location' => $info['location'],
            'user_id' => $this->id,
            'authorized' => 1,
            'authorized_at' => now(),
            'attempt' => 1
        ]);
    }
}
