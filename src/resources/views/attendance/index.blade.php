@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}">
@endsection

@section('content')
<div class="attendance-list-container">
  <h2 class="page-title">勤怠一覧</h2>

  <div class="month-selector">
    <form method="GET" action="{{ route('attendance.list') }}">
      @php
          $currentMonth = \Carbon\Carbon::parse($month);
          $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
          $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
      @endphp

      <button class="month-button" type="submit" name="month" value="{{ $prevMonth }}">← 前月</button>
      <div class="month-label">{{ $currentMonth->format('Y年m月') }}</div>
      <button class="month-button" type="submit" name="month" value="{{ $nextMonth }}">翌月 →</button>
    </form>
  </div>

  <div class="table-wrapper">
    <table class="attendance-table">
      <thead>
        <tr>
          <th>日付</th>
          <th>出勤</th>
          <th>退勤</th>
          <th>休憩</th>
          <th>合計</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      @forelse ($attendances as $attendance)
        @php
          $totalRestSeconds = $attendance->workBreaks->sum(function($break) {
              $start = \Carbon\Carbon::parse($break->break_started_at);
              $end = $break->break_ended_at ? \Carbon\Carbon::parse($break->break_ended_at) : now();
              return $end->diffInSeconds($start);
          });

          $clockIn = $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in) : null;
          $clockOut = $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out) : null;

          $totalWork = $clockIn && $clockOut ? $clockOut->diffInSeconds($clockIn) - $totalRestSeconds : null;
        @endphp

        <tr>
          <td>{{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d(D)') }}</td>
          <td>{{ $clockIn ? $clockIn->format('H:i') : '-' }}</td>
          <td>{{ $clockOut ? $clockOut->format('H:i') : '-' }}</td>
          <td>{{ gmdate('H:i', $totalRestSeconds) }}</td>
          <td>{{ $totalWork ? gmdate('H:i', $totalWork) : '-' }}</td>
          <td><a href="#" class="detail-link">詳細</a></td>
        </tr>
      @empty
        <tr><td colspan="6">勤怠データがありません。</td></tr>
      @endforelse
      </tbody>
    </table>

    <div class="pagination">
      {{ $attendances->appends(['month' => $month])->links() }}
    </div>
  </div>
</div>
@endsection
