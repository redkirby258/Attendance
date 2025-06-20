<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン | COACHTECH</title>
  <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
  <div class="login-container">
    <h1 class="login-title">ログイン</h1>
    @if(session('error'))
      <div class="error-message">{{ session('error') }}</div>
    @endif
    <form class="login-form" method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" required>
      </div>
      <button type="submit" class="login-button">ログイン</button>
    </form>
  </div>
</body>

</html>
