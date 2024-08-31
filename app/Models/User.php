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
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_available',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function store($data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = self::create($data);

        return $user;
    }

    public static function userFindByEmail($email)
    {
        $user = self::where('email', $email)->orderBy('created_at', 'desc')->first();

        return $user;
    }

    public static function modify($user, $data)
    {
        return $user->update($data);
    }

    public static function findOne($id)
    {
        return self::find($id);
    }
    public static function findAllActive()
    {
        return self::where('role', UserRole::User)->where('is_available', true)->withCount(['workingTasks as working_tasks_count' => function ($query) {
            $query->where('status', TaskStatus::Pending)
                ->orWhere('status', TaskStatus::Progress);
        }])->orderBy('working_tasks_count', 'asc')->get();
    }

    public function ownTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function workingTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'worker_id');
    }
}
