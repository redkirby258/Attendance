@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/show.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="title">勤怠詳細</h2>
    <div class="attendance-box">
        <table class="attendance-table">
            <tr>
                <th>名前</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{ $date->format('Y年 m月d日') }}</td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>{{ $start_time }} ～ {{ $end_time }}</td>
            </tr>
            <tr>
                <th>休憩</th>
                <td>{{ $break_start }} ～ {{ $break_end }}</td>
            </tr>
            <tr>
                <th>備考</th>
                <td><input type="text" class="memo-input" value="{{ $note }}" readonly></td>
            </tr>
        </table>
        <div class="btn-container">
            <a href="{{ route('attendance.edit', $id) }}" class="btn">修正</a>
        </div>
    </div>
</div>
@endsection
