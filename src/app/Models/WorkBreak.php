<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkBreak extends Model
{
    use HasFactory;

    protected $table = 'breaks';
    protected $fillable = [
        'attendance_id',
        'break_started_at',
        'break_ended_at',
    ];

    protected $dates = ['break_started_at', 'break_ended_at'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    // アクセサ：休憩時間（分）
    public function getDurationAttribute(): int
    {
        if ($this->break_started_at && $this->break_ended_at) {
            return Carbon::parse($this->break_ended_at)
                         ->diffInMinutes(Carbon::parse($this->break_started_at));
        }
        return 0;
    }
}
