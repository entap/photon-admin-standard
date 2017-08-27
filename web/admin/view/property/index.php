<?php require_once __DIR__ . '/../common/header.php' ?>
<?php if (isset($dev)) { ?>
	<p class="alert alert-danger">スキーマ編集モードです。プロパティとプロパティグループの管理ができます。</p>
<?php } ?>
<div class="row">
	<div class="col-xs-3">
		<div class="list-group">
			<?php foreach ($property_groups as $record) { ?>
				<a href="<?= modify_url_query(get_request_url(), 'c[property_group_id]=' . $record['id']) ?>" class="list-group-item <?= $c['property_group_id'] == $record['id'] ? 'active' : '' ?>"><?= h($record['name']) ?></a>
			<?php } ?>
		</div>
		<?php if (!isset($dev)) { ?>
			<div class="list-group">
				<a href="property.php?action=import" class="list-group-item" data-toggle="remote-modal"><span class="glyphicon glyphicon-import"></span> 設定の読込</a>
				<a href="property.php?action=export" class="list-group-item"><span class="glyphicon glyphicon-export"></span> 設定の保存</a>
			</div>
		<?php } ?>
		<?php if (admin_has_role('*')) { ?>
			<div class="list-group">
				<?php if (isset($dev)) { ?>
					<a href="property.php" class="list-group-item">スキーマの編集を終了</a>
					<a href="property.php?action=dev_group" class="list-group-item" data-toggle="remote-modal"><span class="glyphicon glyphicon-plus"></span> グループを作成</a>
					<a href="property.php?action=dev_import" class="list-group-item" data-toggle="remote-modal"><span class="glyphicon glyphicon-import"></span> スキーマの読込</a>
					<a href="property.php?action=dev_export" class="list-group-item"><span class="glyphicon glyphicon-export"></span> スキーマの保存</a>
				<?php } else { ?>
					<a href="property.php?dev=1" class="list-group-item"><span class="glyphicon glyphicon-warning-sign"></span> スキーマの編集</a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	<div class="col-xs-9">
		<div class="page-header">
			<h1><?= h($property_group['name']) ?></h1>
		</div>
		<?php if ($property_group['description'] !== '') { ?>
			<p><?= h($property_group['description']) ?></p>
		<?php } ?>
		<?php if (isset($dev)) { ?>
			<p class="text-right">
				<a href="property.php?action=dev_edit&property_group_id=<?= $property_group['id'] ?>" class="btn btn-default" data-toggle="remote-modal">プロパティを作成</a>
				<a href="property.php?action=dev_group&id=<?= $property_group['id'] ?>" class="btn btn-default" data-toggle="remote-modal">グループを編集</a>
				<a href="property.php?action=dev_group_delete&id=<?= $property_group['id'] ?>" class="btn btn-default" onclick="return confirm('本当に削除しますか？');">グループを削除</a>
			</p>
		<?php } ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="col-xs-4">名前</th>
				<th class="col-xs-6">値</th>
				<th class="col-xs-2">操作</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($properties as $record) { ?>
				<tr>
					<td><?= h($record['name']) ?>(<?= h($record['cd']) ?>)</td>
					<td><?= h($record['value']) ?></td>
					<td>
						<div class="text-center">
							<?php if (isset($dev)) { ?>
								<a href="property.php?action=dev_edit&id=<?= $record['id'] ?>" class="btn btn-default btn-sm" data-toggle="remote-modal">編集</a>
							<?php } else { ?>
								<a href="property.php?action=edit&id=<?= $record['id'] ?>" class="btn btn-primary btn-sm" data-toggle="remote-modal"><span class="glyphicon glyphicon-edit"></span> 変更</a>
							<?php } ?>
						</div>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
