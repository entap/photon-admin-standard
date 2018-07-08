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
		<p class="text-center" style="font-size: 80px; color: darkgray"><span class="glyphicon glyphicon-user"></span></p>
		<?= form_error('login') ?>
		<?= form_text('username', 'class="form-control" placeholder="ユーザ名"') ?>
		<?= form_password('password', 'class="form-control" placeholder="パスワード"') ?>
		<button type="submit" class="btn btn-primary btn-block">ログイン</button>
	</form>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
</body>
</html>