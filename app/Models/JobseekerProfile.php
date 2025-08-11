<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'job_seeker_type', // Added new field for formal/informal status
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthday',
        'sex',
        'photo',
        'civilstatus',
        'street',
        'barangay',
        'municipality',
        'province',
        'religion',
        'contactnumber',
        'email',
        'disability',
        'is_4ps',
        'employmentstatus',
        'education',
        'work_experience',
        'skills',
    ];
}
