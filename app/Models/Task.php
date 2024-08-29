<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getAllByOwner($task,$user)
    {
        return $task->with(['owner','worker'])->where('user_id',$user->id)->orderBy('created_at', 'desc')->paginate(10);
    }
    public function getAllByWoker($task,$user)
    {
        return $task->with(['owner','worker'])->where('worker_id',$user->id)->orderBy('created_at', 'desc')->paginate(10);
    }
    public function findOneByOWner($id,$user,$task = new Task())
    {
        return $task->with(['owner','worker'])->where('id',$id)->where('user_id',$user->id)->first();
    }
    public function findOnebyWorker($id,$user,$task = new Task())
    {
        return $task->with(['owner','worker'])->where('id',$id)->where('worker_id',$user->id)->first();
    }
    public function store($data,$task,$user)
    {
        $data['user_id'] = $user->id;
        return $task->create($data);
    }
    public function modifyByOwner($task, $data)
    {
        return $task->update($data);
    }
    public function modifyByWorker($task, $data)
    {
        if(isset($data['status'])){
            return $task->update(['status' => $data['status'] ]);
        }
        return true;
    }
    public function remove($task)
    {
        return $task->delete();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class,'worker_id');
    }
}
