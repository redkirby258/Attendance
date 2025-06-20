<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
@yield('css')
</head>
<body>
  <div class="app">
    <header class="header">
      <div class="header__inner">
        <h1 class="header__logo">COACHTECH</h1>
        @yield('link') {{-- メニューがある場合に使う --}}
      </div>
    </header>

    <main class="main">
      @yield('content')
    </main>
  </div>
</body>
</html>
