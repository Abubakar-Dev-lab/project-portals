<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'manager_id',
        'title',
        'description',
        'status'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function statuses()
    {
        return [
            'pending' => 'Pending',
            'active' => 'Active',
            'completed' => 'Completed'
        ];
    }
}
