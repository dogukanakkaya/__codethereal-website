<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Authorize extends Model
{
    /**
     * @var string
     */
    protected $table = 'authorizes';

    /**
     * @var array
     */
    protected $dates = ['authorized_at'];

    /**
     * @var string
     */
    protected $fillable = [
        'user_id',
        'authorized',
        'token',
        'ip_address',
        'browser',
        'os',
        'location',
        'attempt',
        'authorized_at',
        'expires_at'
    ];

    /**
     * @param $date
     */
    public function setAuthorizedAtAttribute($date)
    {
        $this->attributes['authorized_at'] = Carbon::parse($date);
    }

    /**
     * @return mixed
     */
    public static function active()
    {
        return with(new self)
            ->where('user_id', Auth::id())
            ->where('ip_address', request()->ip())
            ->where('authorized', true)
            ->first();
    }

    /**
     * @param $token
     * @return mixed
     */
    public static function validateToken($token = null)
    {
        $query = self::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if ($query) {
            return $query->update([
                'authorized' => true,
                'authorized_at' => now(),
                'token' => NULL
            ]);
        }
        return false;
    }

    /**
     * @return mixed
     */
    public static function make()
    {
        return self::firstOrCreate(
            [
                'ip_address' => request()->ip(),
                'user_id' => Auth::id(),
            ],
            [
                'expires_at' => now()->addHour()
            ]
        );
    }
}
