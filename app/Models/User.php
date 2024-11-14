<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'avatar',
        'email',
        'password',
        'directory',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function handleThirdParty($platform, $third_party){
        $user = User::firstOrCreate(
            ['email' => $third_party->getEmail()],
            [
            'email' => $third_party->getEmail(),
            'nickname' => $third_party->getNickname(),
            'avatar' => $third_party->getAvatar(),
            'name' => $third_party->getName(),
        ]);
        $user->addLink($platform,$third_party);
        return $user;
    }

    /**
     * Get all of the events for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Party::class, 'owner_id');
    }

    /**
     * The parties that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parties()
    {
        return $this->belongsToMany(Party::class, 'party_users');
    }

    public function addParty($party_id){
        if(!$this->parties->contains($party_id)){
            $this->parties()->attach($party_id);
            return true;
        }
        return false;
    }

    public function ownedParties(){
        return $this->hasMany(Party::class, 'owner_id');
    }

    public function removeParty($party_id){
        if($this->parties->contains($party_id)){
            $this->parties()->detach($party_id);
            return true;
        }
        return false;
    }

    /**
     * The roles that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'role_user', 'user_id', 'role_id');
    }

    public function addRole($role_name){
        $roleId = Roles::findRole($role_name)->id;
        if(!$this->roles->contains($roleId)){
            $this->roles()->attach($roleId);
        }
    }
    public function removeRoll($role_name){
        $role = Roles::findRole($role_name);
        if($this->roles->contains($role->id)){
            $this->roles()->detach($role->id);
        }
    }

    public function isAdmin(){
        return $this->roles()->contains('admin');
    }

    public function socialLinks(){
        return $this->hasMany(SocialLinks::class);
    }

    public function addLink($platform,$third_party){
        $this->socialLinks()->updateOrCreate([
            'platform' => $platform,
            'user_id' => $this->id,
        ],[
            'token' => $third_party->token,
            'expiresIn' => $this->expiresIn,
            'social_id' => $third_party->getId(),
        ]);
    }

    public function saveFile($fileName, $content){
        $filePath = auth()->user()->directory . "/". $fileName; // save files in the user directory
        $stored = Storage::disk('public')->put($filePath, $content);
        if ($stored){
            return $filePath;
        }
        return null;
    }
    
}
