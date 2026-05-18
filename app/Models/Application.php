<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'application';
    protected $primaryKey = 'application_id';
    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'application_date',
        'application_note',
        'template_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
