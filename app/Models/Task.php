<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getAll()
    {
        return Task::orderBy('created_at', 'desc')->get();
    }
    public function findOne($id)
    {
        return Task::find($id);
    }
    public function store($data)
    {
        return Task::create($data);
    }
    public function modify($task, $data)
    {
        return $task->update($data);
    }
    public function remove($task)
    {
        return $task->delete();
    }
}
