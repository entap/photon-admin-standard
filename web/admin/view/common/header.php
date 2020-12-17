<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <link href="/admin/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/admin/assets/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="/admin/assets/css/admin.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Admin</a>
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">ホーム</a></li>
        </ul>
        <ul class="navbar-nav navbar-right">
            <?php if (admin_has_role(['admin_user', 'admin_user_local', 'admin_group', 'admin_role', 'sysmail', 'property', 'log'])) { ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">管理 <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php if (admin_has_role(['admin_user', 'admin_user_local'])) { ?>
                            <li><a class="dropdown-item" href="admin_user.php">管理ユーザ</a></li>
                        <?php } ?>
                        <?php if (admin_has_role('admin_group')) { ?>
                            <li><a class="dropdown-item" href="admin_group.php">管理グループ</a></li>
                        <?php } ?>
                        <?php if (admin_has_role('admin_role')) { ?>
                            <li><a class="dropdown-item" href="admin_role.php">役割</a></li>
                        <?php } ?>
                        <li class="dropdown-divider"></li>
                        <?php if (admin_has_role('sysmail')) { ?>
                            <li><a class="dropdown-item" href="sysmail.php">メールテンプレート</a></li>
                        <?php } ?>
                        <?php if (admin_has_role('property')) { ?>
                            <li><a class="dropdown-item" href="property.php">システム設定</a></li>
                        <?php } ?>
                        <?php if (admin_has_role('log')) { ?>
                            <li><a class="dropdown-item" href="log.php">ログ</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><span
                            class="glyphicon glyphicon-user"></span> <?= h(admin_user_me()['name']) ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin_user.php?action=password" data-toggle="remote-modal">パスワード変更</a>
                    </li>
                    <li><a class="dropdown-item" href="logout.php">ログアウト</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<div class="container">