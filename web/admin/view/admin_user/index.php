<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="row">
	<div class="col-xs-3"><?php include __DIR__ . '/../common/sidebar_admin_user.php' ?></div>
	<div class="col-xs-9">
		<div class="page-header">
			<h1>管理ユーザ</h1>
		</div>
		<p class="pull-right">
			<a href="admin_user.php?action=edit" class="btn btn-primary" data-toggle="remote-modal"><span class="glyphicon glyphicon-plus"></span> 管理ユーザを作成</a>
		</p>
		<p>
		<form action="admin_user.php" class="form-inline">
			<?= form_text('c[keywords]', 'class="form-control" size="20"') ?>
			<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> 検索</button>
		</form>
		</p>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="col-xs-3">フルネーム(ユーザ名)</th>
				<th class="col-xs-3">グループ/役割</th>
				<th class="col-xs-3">最終ログイン日時</th>
				<th class="col-xs-3">操作</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($data['records'] as $record) { ?>
				<tr>
					<td><?= h($record['name']) ?> (<?= h($record['username']) ?>)</td>
					<td><?= h($record['admin_group_name']) ?>/<?= h($record['admin_role_name']) ?></td>
					<td><?= h($record['latest_login']) ?></td>
					<td>
						<div class="text-center">
							<?php if (admin_user_editable($record)) { ?>
								<a href="admin_user.php?action=edit&id=<?= $record['id'] ?>" class="btn btn-primary btn-sm" data-toggle="remote-modal"><span class="glyphicon glyphicon-edit"></span> 編集</a>
							<?php } else { ?>
								<span class="btn btn-primary btn-sm disabled"><span class="glyphicon glyphicon-edit"></span> 編集</span>
							<?php } ?>
							<?php if (admin_user_deletable($record)) { ?>
								<a href="admin_user.php?action=delete&id=<?= $record['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？');"><span class="glyphicon glyphicon-trash"></span> 削除</a>
							<?php } else { ?>
								<span class="btn btn-danger btn-sm disabled"><span class="glyphicon glyphicon-trash"></span> 削除</span>
							<?php } ?>
						</div>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?= $data['html'] ?>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
