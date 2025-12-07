<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'table_name',
        'performed_by',
        'user_type',     
        'user_model',   
        'user_role',
        'details',
        'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];
    protected $appends = ['performer_name', 'performer_type'];
    // Relationship to Admin (when performed_by is an admin ID)
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }

    // Relationship to User (when performed_by is a user ID)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
     public function performer()
    {
        if ($this->user_type === 'admin') {
            return $this->admin;
        } elseif ($this->user_type === 'applicant') {
            return $this->user;
        }
        return null;
    }

   // app/Models/ActionLog.php
        public function getPerformerNameAttribute()
    {
        if ($this->performer) {
            return $this->performer->name;
        }
        
        return match($this->user_type) {
            'guest' => 'Guest User',
            'system' => 'System',
            default => 'Unknown User',
        };
    }

    // Accessor to get performer type
     public function getPerformerEmailAttribute()
    {
        if ($this->performer) {
            return $this->performer->email;
        }
        return null;
    }

    /**
     * Get performer icon based on user_type
     */
    public function getPerformerIconAttribute()
    {
        return match($this->user_type) {
            'admin' => $this->user_role === 'superadmin' 
                ? 'supervised_user_circle' 
                : 'admin_panel_settings',
            'applicant' => 'person',
            'guest' => 'person_outline',
            default => 'help',
        };
    }

    /**
     * Get formatted performer type
     */
    public function getPerformerTypeAttribute()
    {
        if ($this->user_type === 'admin') {
            return $this->user_role === 'superadmin' ? 'superadmin' : 'admin';
        }
        return $this->user_type;
    }
}