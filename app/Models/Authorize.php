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
        'platform',
        'platform_version',
        'browser',
        'browser_version',
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
        return self::where('user_id', Auth::id())
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
        $agent = new \Jenssegers\Agent\Agent();

        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        return self::firstOrCreate(
            [
                'ip_address' => request()->ip(),
                'platform' => $platform,
                'platform_version' => $platformVersion,
                'browser' => $browser,
                'browser_version' => $browserVersion,
                'user_id' => Auth::id(),
            ],
            [
                'expires_at' => now()->addHour()
            ]
        );
    }
}
