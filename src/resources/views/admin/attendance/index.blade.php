@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}">
@endsection

@section('content')
<div class="attendance-list-container">
  <h2 class="page-title">勤怠一覧</h2>

  <div class="month-selector">
    <button class="month-button">← 前月</button>
    <div class="month-label">2023/06</div>
    <button class="month-button">翌月 →</button>
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
      {{-- ここはダミーデータ（実際は @foreach で回す） --}}
      @for ($i = 1; $i <= 30; $i++)
        <tr>
          <td>06/{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}(木)</td>
          <td>09:00</td>
          <td>18:00</td>
          <td>1:00</td>
          <td>8:00</td>
          <td><a href="#" class="detail-link">詳細</a></td>
        </tr>
      @endfor
      </tbody>
    </table>
  </div>
</div>
@endsection
