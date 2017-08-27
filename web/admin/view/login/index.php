<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login</title>
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/admin-login.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div id="login">
	<form action="login.php" method="post">
		<h1>ログイン</h1>
		<?= form_error('login') ?>
		<label>ユーザ名</label>
		<?= form_text('username', 'class="form-control"') ?>
		<label>パスワード</label>
		<?= form_password('password', 'class="form-control"') ?>
		<button type="submit" class="btn btn-primary btn-block">ログイン</button>
	</form>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>