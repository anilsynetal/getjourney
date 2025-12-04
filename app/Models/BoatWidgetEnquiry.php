<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoatWidgetEnquiry extends Model
{
    use HasFactory;

    protected $table = 'boat_widget_enquiries';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'age',
        'nationality',
        'qualification',
        'work_experience',
        'current_occupation',
        'company',
        'destination_country',
        'visa_type',
        'purpose',
        'travel_date',
        'duration',
        'previous_visas',
        'family_status',
        'assets',
        'additional_info',
        'message',
        'status',
        'admin_response',
        'assigned_to',
        'internal_notes',
        'user_ip',
        'user_agent',
        'responded_at',
        'closed_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin user assigned to this enquiry
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    /**
     * Query scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('visa_category', $category);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_to');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Status management methods
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
        return $this;
    }

    public function markAsResponded($response)
    {
        $this->update([
            'status' => 'responded',
            'admin_response' => $response,
            'responded_at' => now(),
        ]);
        return $this;
    }

    public function markAsClosed()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
        return $this;
    }

    public function assignTo($userId)
    {
        $this->update(['assigned_to' => $userId]);
        return $this;
    }

    public function addInternalNote($note)
    {
        $currentNotes = $this->internal_notes ?? '';
        $timestamp = now()->format('Y-m-d H:i:s');
        $newNote = "\n[{$timestamp}] {$note}";
        $this->update(['internal_notes' => $currentNotes . $newNote]);
        return $this;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'new' => 'badge-danger',
            'read' => 'badge-info',
            'responded' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-light',
        };
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'new' => 'New Enquiry',
            'read' => 'Read',
            'responded' => 'Responded',
            'closed' => 'Closed',
            default => 'Unknown',
        };
    }

    /**
     * Accessors for view compatibility
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    public function getVisaCategoryAttribute()
    {
        return $this->visa_type;
    }
}
