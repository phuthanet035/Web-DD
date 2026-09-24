<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'category_id',
        'title',
        'description',
        'device_code',
        'location',
        'priority',
        'status',
        'requester_name',
        'requester_phone',
        'requester_email',
        'attachment',
        'assigned_to',
        'resolution_note',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TicketHistory::class)->latest();
    }

    public static function statusLabels(): array
    {
        return [
            'pending'       => 'รอดำเนินการ',
            'assigned'      => 'มอบหมายงานแล้ว',
            'in_progress'   => 'กำลังดำเนินการ',
            'waiting_parts' => 'รออะไหล่/อุปกรณ์',
            'resolved'      => 'ซ่อมเสร็จสิ้น',
            'cancelled'     => 'ยกเลิก',
        ];
    }

    public static function priorityLabels(): array
    {
        return [
            'low'    => 'ต่ำ',
            'medium' => 'ปานกลาง',
            'high'   => 'สูง',
            'urgent' => 'เร่งด่วนมาก',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::priorityLabels()[$this->priority] ?? $this->priority;
    }
}
