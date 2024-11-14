<?php

namespace App\Observers;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user){
        $random_hash = Str::random(10) . ' ' . $user->nickname;
        $random_hash = md5($random_hash);
        $directory = env('USER_DIRECTORIES', 'images') . '/' . $random_hash ;
        $user->directory = $directory;
        $user->save();
        Storage::disk('public')->makeDirectory($directory);
    }
}
