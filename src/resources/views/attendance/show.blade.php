@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="title">勤怠詳細・編集</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attendance.show', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td><input type="date" name="work_date" value="{{ old('work_date', \Carbon\Carbon::parse($attendance->work_date)->format('Y-m-d')) }}" required></td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="time" name="clock_in" value="{{ old('clock_in', $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '') }}">
                    ～
                    <input type="time" name="clock_out" value="{{ old('clock_out', $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '') }}">
                </td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>
                    <input type="time" name="break_started_at" value="{{ old('break_started_at', $attendance->break_started_at ? \Carbon\Carbon::parse($attendance->break_started_at)->format('H:i') : '') }}">
                    ～
                    <input type="time" name="break_ended_at" value="{{ old('break_ended_at', $attendance->break_ended_at ? \Carbon\Carbon::parse($attendance->break_ended_at)->format('H:i') : '') }}">
                </td>
            </tr>
            <tr>
                <th>備考</th>
                <td><input type="text" name="note" value="{{ old('note', $attendance->note) }}"></td>
            </tr>
        </table>
    </form>
     <form action="{{ route('attendance.requestEdit', $attendance->id) }}" method="POST" onsubmit="return confirm('修正申請を送信しますか？');" style="margin-top: 10px;">
        @csrf
        <button type="submit" class="btn btn-warning">修正申請</button>
    </form>
</div>
@endsection
