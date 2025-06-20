<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECH 勤怠管理</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @yield('css')
</head>
<body>
  <div class="container">
    <header class="header">
      <div class="header__logo">COACHTECH</div>
      <nav class="header__nav">
        <a href="#">勤怠</a>
        <a href="#">勤怠一覧</a>
        <a href="#">申請</a>
        <a href="#">ログアウト</a>
      </nav>
    </header>
    <main class="main">
      @yield('content')
    </main>
  </div>
</body>
</html>
