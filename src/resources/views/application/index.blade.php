@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/application/index.css') }}">
@endsection

@section('content')
<div class="container">
  <h2 class="page-title">申請一覧</h2>
  <div class="tabs">
    <button class="tab active" data-target="pending">承認待ち</button>
    <button class="tab" data-target="approved">承認済み</button>
  </div>

  {{-- 承認待ち一覧 --}}
  <table class="application-table tab-content active" id="pending">
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
      @forelse ($pendingApplications as $application)
        <tr>
          <td>承認待ち</td>
          <td>{{ $application->user->name }}</td>
          <td>{{ \Carbon\Carbon::parse($application->work_date)->format('Y/m/d') }}</td>
          <td>{{ $application->reason }}</td>
          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('Y/m/d') }}</td>
          <td><a href="{{ route('applications.show', $application->id) }}">詳細</a></td>
        </tr>
      @empty
        <tr><td colspan="6">承認待ちの申請はありません。</td></tr>
      @endforelse
    </tbody>
  </table>

  {{-- 承認済み一覧 --}}
  <table class="application-table tab-content" id="approved">
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
      @forelse ($approvedApplications as $application)
        <tr>
          <td>承認済み</td>
          <td>{{ $application->user->name }}</td>
          <td>{{ \Carbon\Carbon::parse($application->work_date)->format('Y/m/d') }}</td>
          <td>{{ $application->reason }}</td>
          <td>{{ \Carbon\Carbon::parse($application->created_at)->format('Y/m/d') }}</td>
          <td><a href="{{ route('applications.show', $application->id) }}">詳細</a></td>
        </tr>
      @empty
        <tr><td colspan="6">承認済みの申請はありません。</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<script>
  // タブ切り替えのJS（必要に応じて外部JSにしてもOK）
  document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

      tab.classList.add('active');
      document.getElementById(tab.dataset.target).classList.add('active');
    });
  });
</script>
@endsection
