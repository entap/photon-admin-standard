<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="page-header">
	<h1>メールテンプレート</h1>
</div>
<p class="pull-right">
	<a href="sysmail.php?action=edit" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> メールテンプレートを作成</a>
</p>
<p>
<form action="sysmail.php" class="form-inline">
	<?= form_select_assoc('c[sysmail_type_id]', 'sysmail_type', 'class="form-control" blank="全て"') ?>
	<?= form_text('c[keywords]', 'class="form-control" size="20"') ?>
	<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> 検索</button>
</form>
</p>
<table class="table table-bordered">
	<thead>
	<tr>
		<th class="col-xs-2">種類</th>
		<th class="col-xs-7">管理用の名前</th>
		<th class="col-xs-3">操作</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($data['records'] as $record) { ?>
		<tr>
			<td><?= h($record['sysmail_type_name']) ?></td>
			<td><?= h($record['name']) ?></td>
			<td>
				<div class="text-center">
					<a href="sysmail.php?action=edit&id=<?= $record['id'] ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> 編集</a>
					<a href="sysmail.php?action=copy&id=<?= $record['id'] ?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-copy"></span> 複製</a>
					<a href="sysmail.php?action=delete&id=<?= $record['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？');"><span class="glyphicon glyphicon-trash"></span> 削除</a>
				</div>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<?= $data['html'] ?>
<?php require_once __DIR__ . '/../common/footer.php' ?>
