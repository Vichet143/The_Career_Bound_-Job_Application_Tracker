<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    // FIXED: Changed to plural 'applications'
    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    protected $fillable = [
        'user_id',
        'generatecv_id',     // FIXED: Replaced template_name with generatecv_id
        'company_name',
        'job_title',
        'application_date',
        'application_note',
        // 'template_name',
        'status',            // FIXED: Added status
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
