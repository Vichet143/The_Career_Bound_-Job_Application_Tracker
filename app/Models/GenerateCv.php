<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateCv extends Model
{
    protected $primaryKey = 'generatecv_id';
    protected $fillable = [
        'user_id',
        'template_id',
        'fullname',
        'job_title',
        'email',
        'phone_number',
        'introduction',
        'project_name',
        'describe_project',
        'education',
        'skills',
        'hobbies',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'template_id');
    }
}
