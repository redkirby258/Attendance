@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/application/index.css') }}">
@endsection


@section('content')
<div class="container">
  <h2 class="page-title">申請一覧</h2>
  <div class="tabs">
    <button class="tab active">承認待ち</button>
    <button class="tab">承認済み</button>
  </div>
  <table class="application-table">
    <thead>
      <tr>
        <th>状態</th>
        <th>名前</th>
        <th>対象日時</th>
        <th>申請理由</th>
        <th>申請日時</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < 8; $i++)
        <tr>
          <td>承認待ち</td>
          <td>西田奈</td>
          <td>2023/06/01</td>
          <td>遅延のため</td>
          <td>2023/06/02</td>
          <td><a href="#">詳細</a></td>
        </tr>
      @endfor
    </tbody>
  </table>
</div>
@endsection
