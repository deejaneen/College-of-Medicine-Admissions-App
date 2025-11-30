<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year',
        'application_deadline',
        'is_active'
    ];

    protected $casts = [
        'application_deadline' => 'datetime',
        'is_active' => 'boolean'
    ];

    protected $appends = ['is_open'];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

   public function getIsOpenAttribute()
    {
        // Make sure we're working with Carbon instances
        $deadline = \Carbon\Carbon::parse($this->application_deadline)->endOfDay();
        $isOpen = $this->is_active && now()->lte($deadline);
        
        return $isOpen;
    }
    // Scope to get active school year
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Validation for school year format (e.g., "2025-2026")
    public static function validateSchoolYearFormat($schoolYear)
    {
        return preg_match('/^\d{4}-\d{4}$/', $schoolYear);
    }

    // Get the start year from the range
    public function getStartYearAttribute()
    {
        return explode('-', $this->school_year)[0];
    }

    // Get the end year from the range
    public function getEndYearAttribute()
    {
        return explode('-', $this->school_year)[1];
    }
}