<?php

namespace App\Models;
use App\Models\WorkBreak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id', 'work_date', 'clock_in', 'clock_out', 'note'
    ];

    public $timestamps = false;

    // 休憩とのリレーション
    public function workBreaks()
    {
        return $this->hasMany(WorkBreak::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function correctionRequests()
    {
        return $this->hasMany(AttendanceCorrectionRequest::class);
    }

   
}
