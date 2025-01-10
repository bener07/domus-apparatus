<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Roles;

class ManageRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:manage-roles {user_id?} {role_name?} {--roles} {--user-role} {--create-role} {--remove-role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage user roles in the app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        $role_name = $this->argument('role_name');
        if($user_id == null){
            $this->line("You didn't specify a user!");
            return 1;
        }
        // find the user with that id
        $user = User::find($user_id);
        // find the role by name
        $role = Roles::findRole($role_name);
        // in case no role is specified or the user want's to know what roles exist then print them
        if($this->option('roles') != null || $role_name == null){
            $this->line('Available Roles:');
            $this->line(Roles::all());
            return 0;
        }
        if($this->option('remove-role') != null){
            $user->removeRole($role_name);
            $this->line("Role '{$role_name}' removed from user '{$user->name}'");
            return 0;
        }
        if($this->option('create-role') != null){
            $role = Roles::create(['name' => $role_name]);
            $this->line($role);
        }
        // show the user roles list
        if($this->option('user-role') != null){
            $this->line($user->roles);
        }
        // add the role to the user
        else{
            $user->addRole($role_name);
        }
    }
}
