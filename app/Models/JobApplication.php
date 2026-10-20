<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_listing_id',
        'full_name',
        'email',
        'phone',
        'cover_letter',
        'resume_media_id',
        'status',
        'ip_address',
    ];

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public function resume()
    {
        return $this->belongsTo(MediaAsset::class, 'resume_media_id');
    }
}
