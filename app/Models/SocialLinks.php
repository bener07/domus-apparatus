<?php

namespace App\Models;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SocialLinks extends Model
{
    protected $table = 'social_links';
    protected $fillable = [
        'platform',
        'social_id',
        'user_id',
        'token',
        'expiresIn'
    ];

    /**
     * Get the user that owns the SocialLinks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function handleCallback($platform){
        $social_user = Socialite::driver($platform)->user();
        $user = User::handleThirdParty($platform, $social_user);
        if($user){
            Auth::login($user);
            return redirect('/dashboard');
        }
        return redirect()->back()->withErrors('It Happened an error authenticating');
    }
}
