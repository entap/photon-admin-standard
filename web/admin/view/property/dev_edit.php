<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">プロパティの編集</h4>
		</div>
		<form action="property.php?action=dev_edit" method="post" enctype="multipart/form-data">
			<?= form_hidden('property[id]') ?>
			<?= form_hidden('property[property_group_id]') ?>
			<div class="modal-body">
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th class="col-xs-3">名前</th>
						<td>
							<?= form_error('property[name]') ?>
							<?= form_text('property[name]', 'class="form-control"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-xs-3">説明文</th>
						<td>
							<?= form_error('property[description]') ?>
							<?= form_textarea('property[description]', 'class="form-control" rows="2"') ?>
						</td>
					</tr>
					</tbody>
				</table>
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th class="col-xs-3">識別子</th>
						<td>
							<?= form_error('property[cd]') ?>
							<?= form_text('property[cd]', 'class="form-control"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-xs-3">プロパティの型</th>
						<td class="form-inline">
							<?= form_error('property[property_type_id]') ?>
							<?= form_select_assoc('property[property_type_id]', 'property_type', 'class="form-control"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-xs-3">デフォルト値</th>
						<td>
							<?= form_error('property[default_value]') ?>
							<?= form_textarea('property[default_value]', 'class="form-control" rows="2"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-xs-3">表示順序</th>
						<td class="form-inline">
							<?= form_error('property[order]') ?>
							<?= form_text('property[order]', 'class="form-control" size="5"') ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<?php if (isset($property['id'])) { ?>
					<a href="property.php?action=dev_delete&id=<?= $property['id'] ?>" class="btn btn-danger">削除</a>
				<?php } ?>
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存</button>
			</div>
		</form>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
