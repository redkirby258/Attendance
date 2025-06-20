@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/clock.css') }}">
@endsection

@section('content')
<div class="attendance-container">
    {{-- ステータス --}}
    <div class="status-badge">{{ $status }}</div>

    {{-- 日付・時刻 --}}
    <div class="date">{{ $date }}</div>
    <div class="time">{{ $time }}</div>

    {{-- メッセージ --}}
    @if(session('success'))
        <p class="flash flash--success">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="flash flash--error">{{ session('error') }}</p>
    @endif

    {{-- 出勤ボタン（勤務外のみ表示） --}}
    @if($status === '勤務外')
        <form action="{{ route('attendance.clockIn') }}" method="POST">
            @csrf
            <button class="clock-button" type="submit">出勤</button>
        </form>
    @endif

    {{-- 出勤中で休憩未開始 --}}
    @if($status === '出勤中' && !$breakDone)
    <form action="{{ route('attendance.breakStart') }}" method="POST">@csrf
      <button class="break-button">休憩</button>
    </form>
    @endif

    {{-- 休憩中 --}}
    @if($status === '休憩中')
      <form action="{{ route('attendance.breakEnd') }}" method="POST">@csrf
        <button class="breakend-button">休憩終了</button>
      </form>
    @endif

    {{-- 出勤中 or 休憩中 → 退勤ボタン --}}
    @if(in_array($status, ['出勤中','休憩中']))
    <form action="{{ route('attendance.clockOut') }}" method="POST">@csrf
      <button class="clockout-button">退勤</button>
    </form>
    @endif
</div>
@endsection
