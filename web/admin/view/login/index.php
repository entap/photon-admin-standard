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
<div class="container">
    <div class="row">
        <div class="col-5 mx-auto">
            <div class="card my-5">
                <div class="card-body">
                    <h1 class="card-title text-center h5">ログイン</h1>
                    <form action="login.php" method="post">
                        <?= form_error('login') ?>
                        <?= form_text('username', 'class="form-control my-2" placeholder="ユーザ名"') ?>
                        <?= form_password('password', 'class="form-control my-2" placeholder="パスワード"') ?>
                        <button type="submit" class="btn btn-primary btn-block">ログイン</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/admin/assets/js/jquery.min.js"></script>
<script src="/admin/assets/js/bootstrap.min.js"></script>
</body>
</html>