<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_available',
        'role'
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

    public function store($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return $user;
    }

    public function userFindByEmail($email)
    {
        $user = User::where('email',$email)->orderBy('created_at','desc')->first();

        return $user;
    }

    public function modify($user, $data)
    {
        return $user->update($data);
    }

    public function findOne($id,$user = new User())
    {
        return $user->find($id);
    }
    public function findAllActive($user = new User())
    {
        return $user->where('role',UserRole::User)->where('is_available',true)->withCount(['workingTasks as working_tasks_count' => function ($query){
            $query->where('status', TaskStatus::Pending)
                  ->orWhere('status',TaskStatus::Progress);
        }])->orderBy('working_tasks_count','asc')->get();
    }

    public function ownTasks(): HasMany
    {
        return $this->hasMany(Task::class,'user_id');
    }

    public function workingTasks(): HasMany
    {
        return $this->hasMany(Task::class,'worker_id');
    }
}
