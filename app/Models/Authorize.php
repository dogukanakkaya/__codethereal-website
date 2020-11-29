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
        'authorized_at'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', Auth::id());
    }

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
            ->where('ip_address', request()->ip())
            ->where('authorized', true)
            ->where('authorized_at', '<', now()->addHour())
            ->first();
    }

    /**
     * @param $token
     */
    public static function validateToken($token = null)
    {
        $query = self::where([
            'token' => $token,
        ])->first();

        if ($query) {
            $query->update([
                'authorized' => true,
                'authorized_at' => now(),
                'token' => NULL
            ]);

            return self::active();
        }
    }

    /**
     * @return mixed
     */
    public static function make()
    {
        return self::firstOrCreate([
            'ip_address' => request()->ip(),
            'user_id' => Auth::id(),
        ]);
    }
}
