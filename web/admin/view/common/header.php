<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin</title>
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/admin.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php">Admin</a>
		</div>
		<ul class="nav navbar-nav">
			<li><a href="index.php">ホーム</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<?php if (admin_has_role(['admin_user', 'admin_user_local', 'admin_group', 'admin_role', 'sysmail', 'property', 'log'])) { ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">管理 <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php if (admin_has_role(['admin_user', 'admin_user_local'])) { ?>
							<li><a href="admin_user.php">管理ユーザ</a></li>
						<?php } ?>
						<?php if (admin_has_role('admin_group')) { ?>
							<li><a href="admin_group.php">管理グループ</a></li>
						<?php } ?>
						<?php if (admin_has_role('admin_role')) { ?>
							<li><a href="admin_role.php">役割</a></li>
						<?php } ?>
						<li class="divider"></li>
						<?php if (admin_has_role('sysmail')) { ?>
							<li><a href="sysmail.php">メールテンプレート</a></li>
						<?php } ?>
						<?php if (admin_has_role('property')) { ?>
							<li><a href="property.php">システム設定</a></li>
						<?php } ?>
						<?php if (admin_has_role('log')) { ?>
							<li><a href="log.php">ログ</a></li>
						<?php } ?>
					</ul>
				</li>
			<?php } ?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?= h(admin_user_me()['name']) ?>
					<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="admin_user.php?action=password" data-toggle="remote-modal">パスワード変更</a></li>
					<li><a href="logout.php">ログアウト</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>
<div class="container">