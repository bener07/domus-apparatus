<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Requisicao;


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
        'avatar',
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

    public static function handleThirdProduct($platform, $third_Product){
        $user = User::firstOrCreate(
            ['email' => $third_Product->getEmail()],
            [
            'email' => $third_Product->getEmail(),
            'nickname' => $third_Product->getNickname(),
            'avatar' => $third_Product->getAvatar(),
            'name' => $third_Product->getName(),
        ]);
        $user->addLink($platform,$third_Product);
        return $user;
    }

    /**
     * The products that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requisicoes()
    {
        return $this->belongsToMany(Product::class, 'requisicoes')->where('requisicoes.status', 'requisitado');
    }

    public function entregues(){
        return $this->belongsToMany(Product::class, 'requisicoes')->where('requisicoes.status', 'entregue');
    }

    public function pendentes(){
        return $this->belongsToMany(Product::class, 'requisicoes')->where('requisicoes.status', 'pendente');
    }

    public function requisitar($request){
        $product = Product::find($request->product_id);
        return Requisicao::requisitar($this, $product, $request);
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

    public function hasRole($role_name){
        return $this->roles->contains('name', $role_name);
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
        return $this->hasRole('admin');
    }

    public function socialLinks(){
        return $this->hasMany(SocialLinks::class);
    }

    public function addLink($platform,$third_Product){
        $this->socialLinks()->updateOrCreate([
            'platform' => $platform,
            'user_id' => $this->id,
        ],[
            'token' => $third_Product->token,
            'expiresIn' => $this->expiresIn,
            'social_id' => $third_Product->getId(),
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
