<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
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
        'department_id',
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

    protected static function boot()
    {
        parent::boot();

        // Set default value when creating a new user
        static::creating(function ($user) {
            if (is_null($user->password)) {
                $user->password = Hash::make(now()); // Default role
            }
            if(is_null($user->department_id)){
                $user->department_id = Department::first()->id ?? 1; // Default department
            }
        });
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

    public function cart(){
    	return $this->hasOne(Cart::class);
    }

    /**
     * The products that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class, 'user_id')->where('requisicoes.status', 'requisitado');
    }

    public function entregues(){
        return $this->hasMany(Requisicao::class, 'user_id')->where('requisicoes.status', 'entregue');
    }

    public function pendentes(){
        return $this->hasMany(Requisicao::class, 'user_id')->where('requisicoes.status', 'pendente');
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

    public function getRoleIds($roles){
        $roleIds = [];
        foreach($roles as $role){
            array_push($roleIds, Roles::findRole($role)->id);
        }
        return $roleIds;
    }

    public function syncRoles($roles){
        $this->roles()->sync($this->getRoleIds($roles));
    }

    public function removeRole($role_name){
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

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function ownedDepartment(): HasOne
    {
        return $this->hasOne(Department::class, 'manager_id');
    }

    public function attachDepartment($department){
        $department = Department::findDepartment($department);

        $this->department()->associate($department);
        $this->save();
    }

    public function tags(){
        return $this->hasMany(Tags::class, 'tags');
    }
}
