<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'user_id',
        'school_year_id',

        // Personal Information
        'email_address',
        'last_name',
        'first_name',
        'middle_name',
        'date_of_birth',
        'citizenship',
        'permanent_home_address',
        'municipality',
        'province',
        'zip_code',
        'active_gmail_account',
        'cellphone_number',
        'civil_status',
        'sex',
        'religion',
        'indigenous_group',
        'indigenous_group_specify',
        'physical_disability',
        'physical_disability_specify',
        'community_health_volunteer',
        'community_health_organization',

        // Educational Background
        'bachelors_degree',
        'name_of_school_bachelors_degree',
        'gwa_for_bachelors_degree',
        'bachelors_transcript_of_records',
        'graduate_studies',
        'name_of_school_graduate_studies',
        'date_of_graduation',
        'graduate_studies_transcript_of_records',

        // Family Information
        'father_first_name',
        'father_middle_name',
        'father_last_name',
        'father_occupation',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'mother_occupation',
        'guardian_first_name',
        'guardian_middle_name',
        'guardian_last_name',
        'guardian_occupation',
        'combined_annual_income_of_family',

        // NMAT Information
        'nmat_score',
        'nmat_certification',
        'date_of_nmat_examination',

        // Essay
        'why_choose_southern_luzon_state_university',

        // System fields
        'application_status',
    ];
    // ADD THESE CASTS FOR PROPER DATA HANDLING
    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_graduation' => 'date',
        'date_of_nmat_examination' => 'date',
        'bachelors_transcript_of_records' => 'array',
        'graduate_studies_transcript_of_records' => 'array',
        'nmat_certification' => 'array',
        'gwa_for_bachelors_degree' => 'decimal:2',
        'nmat_score' => 'integer',
    ];

    protected $attributes = [
        'application_status' => 'For Review'
    ];

    protected $hidden = [
        // Add any sensitive fields you don't want exposed in APIs
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // ADD THESE HELPER METHODS
    public function getFullNameAttribute()
    {
        return "{$this->lastName}, {$this->firstName} " . ($this->middleName ? "{$this->middleName}" : "");
    }

    // In your Application model (App\Models\Application.php)

    // UPDATE THESE SCOPES:
    public function scopePending($query)
    {
        return $query->where('application_status', 'For Review');
    }

    public function scopeAccepted($query)
    {
        return $query->where('application_status', 'Accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('application_status', 'Rejected');
    }

    public function scopeWaitlist($query)
    {
        return $query->where('application_status', 'Waitlist');
    }

    // UPDATE THESE HELPER METHODS:
    public function isPending()
    {
        return $this->application_status === 'For Review';
    }

    public function isAccepted()
    {
        return $this->application_status === 'Accepted';
    }

    public function isRejected()
    {
        return $this->application_status === 'Rejected';
    }

    public function isWaitlist()
    {
        return $this->application_status === 'Waitlist';
    }
}