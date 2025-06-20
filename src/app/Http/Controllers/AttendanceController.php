<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Models\WorkBreak;          // ← モデル名変更
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /* ===== 画面表示 ===== */
    public function showClockInForm()
    {
        $user   = Auth::user();
        $today  = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
                                ->where('work_date', $today)
                                ->first();

        $status = '勤務外';

        if ($attendance) {
            $hasOngoingBreak = $attendance->breaks()->whereNull('break_ended_at')->exists();

            if ($hasOngoingBreak) {
                $status = '休憩中';
            } elseif ($attendance->clock_in && !$attendance->clock_out) {
                $status = '出勤中';
            } elseif ($attendance->clock_in && $attendance->clock_out) {
                $status = '退勤済';
            }
        }

        return view('attendance.clock', [
            'status' => $status,
            'breakDone' => $attendance && $attendance->breaks()->exists(), // 休憩開始済みか判定用
            'date'   => $today->format('Y年n月j日(D)'),
            'time'   => Carbon::now()->format('H:i'),
        ]);
    }

    /* ===== 出勤 ===== */
    public function clockIn(Request $request)
    {
        $user  = Auth::user();
        $today = Carbon::today();

        if (Attendance::where('user_id', $user->id)->where('work_date', $today)->exists()) {
            return back()->with('error', '本日はすでに出勤済みです。');
        }

        Attendance::create([
            'user_id'   => $user->id,
            'work_date' => $today,
            'clock_in'  => Carbon::now()->format('H:i:s'),
        ]);

        return redirect()->route('attendance.show')->with('success', '出勤しました！');
    }

    /* ===== 休憩開始（1回だけ） ===== */
    public function breakStart()
    {
        $attendance = $this->todayAttendanceOrFail();

        if ($attendance->breaks()->whereNull('break_ended_at')->exists()) {
            return back()->with('error', '既に休憩中です。');
        }

        $attendance->breaks()->create([
            'break_started_at' => Carbon::now()->format('H:i:s'),
        ]);

        return back()->with('success', '休憩を開始しました。');
    }

    /* ===== 休憩終了 ===== */
    public function breakEnd()
    {
        $attendance = $this->todayAttendanceOrFail();

        $ongoing = $attendance->breaks()->whereNull('break_ended_at')->first();

        if (!$ongoing) {
            return back()->with('error', '現在休憩中ではありません。');
        }

        $ongoing->update(['break_ended_at' => Carbon::now()->format('H:i:s')]);

        return back()->with('success', '休憩を終了しました。');
    }

    /* ===== 退勤 ===== */
    public function clockOut()
    {
        $attendance = $this->todayAttendanceOrFail();

        if ($attendance->clock_out) {
            return back()->with('error', 'すでに退勤済みです。');
        }

        // 未終了の休憩があれば自動終了
        $attendance->breaks()
                   ->whereNull('break_ended_at')
                   ->update(['break_ended_at' => Carbon::now()->format('H:i:s')]);

        $attendance->update(['clock_out' => Carbon::now()->format('H:i:s')]);

        return redirect()->route('attendance.show')->with('success', 'お疲れ様でした。');
    }

    /* ===== 当日の勤怠レコード取得 ===== */
    private function todayAttendanceOrFail()
    {
        return Attendance::where('user_id', Auth::id())
                         ->where('work_date', Carbon::today())
                         ->firstOrFail();
    }

    
    public function attendanceList(Request $request)
    {
    $user = Auth::user();

    // クエリ or デフォルトで当月
    $month = $request->input('month', Carbon::now()->format('Y-m'));
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    // 自分の今月の勤怠データを取得（休憩情報も取得）
    $attendances = Attendance::with('workBreaks') // リレーション名は正確に
        ->where('user_id', $user->id)
        ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
        ->orderBy('work_date', 'asc')
        ->paginate(30); // 必要に応じて変更

    return view('attendance.index', [
        'attendances' => $attendances,
        'month' => $month,
    ]);
    }

    public function showOrUpdate(Request $request, $id)
{
    $user = Auth::user();

    $attendance = Attendance::where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    if ($request->isMethod('put')) {
        $request->validate([
            'work_date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after_or_equal:clock_in',
            'break_started_at' => 'nullable|date_format:H:i',
            'break_ended_at' => 'nullable|date_format:H:i|after_or_equal:break_started_at',
            'note' => 'nullable|string|max:255',
        ]);

        $attendance->work_date = $request->input('work_date');
        $attendance->clock_in = $request->input('clock_in');
        $attendance->clock_out = $request->input('clock_out');
        $attendance->break_started_at = $request->input('break_started_at');
        $attendance->break_ended_at = $request->input('break_ended_at');
        $attendance->note = $request->input('note');
        $attendance->save();

        return redirect()->route('attendance.show', $attendance->id)
            ->with('success', '勤怠情報を更新しました。');
    }

    return view('attendance.show', [
        'user' => $user,
        'attendance' => $attendance,
    ]);
}

 public function requestEdit($id)
    {
        $user = Auth::user();

        // 自分の勤怠データを取得
        $attendance = Attendance::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 既に申請中がないかチェック
        $exists = AttendanceCorrectionRequest::where('attendance_id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', '既に修正申請中の勤怠があります。');
        }

        // Attendanceテーブルの申請状態カラム更新
        $attendance->edit_request_status = 'pending';  // 'pending'は申請中の意味
        $attendance->save();

        // 修正申請レコード作成
        AttendanceCorrectionRequest::create([
            'attendance_id' => $id,
            'user_id' => $user->id,
            'status' => 'pending',
            'requested_at' => now(),
            // 必要に応じて申請内容を追加
        ]);

        return redirect()->route('attendance.show', $id)->with('success', '修正申請が送信されました。');
    }

}
