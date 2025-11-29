<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'job_id', 'cv', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
