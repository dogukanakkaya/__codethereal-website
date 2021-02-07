<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        'device',
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
        $info = self::getAuthorizeInformation();

        return self::where('user_id', Auth::id())
            //->where('ip_address', $info['ip_address'])
            ->where('platform', $info['platform'])
            ->where('browser', $info['browser'])
            ->where('device', $info['device'])
            ->where('location', $info['location'])
            ->where('authorized', 1)
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
        $info = self::getAuthorizeInformation();

        return self::firstOrCreate(
            [
                'ip_address' => $info['ip_address'],
                'platform' => $info['platform'],
                'platform_version' => $info['platform_version'],
                'browser' => $info['browser'],
                'browser_version' => $info['browser_version'],
                'device' => $info['device'],
                'location' => $info['location'],
                'user_id' => $info['user_id']
            ],
            [
                'expires_at' => now()->addHour(),
                'token' => Str::random(64)
            ]
        );
    }

    private static function getAuthorizeInformation()
    {
        $ip = request()->ip();
        $userAgent = request()->server('HTTP_USER_AGENT');
        $agent = agent($userAgent);

        // If testing do not send request to find location every time.
        if (app()->environment('testing', 'local')){
            $location = ['country' => 'Turkey', 'city' => 'Istanbul'];
        }else{
            $location = location($ip);
        }
        return array_merge(
            $agent,
            $location,
            ['ip_address' => $ip],
            ['location' => $location['country'] . "-" . $location['city']],
            ['user_id' => Auth::id()]
        );
    }
}
