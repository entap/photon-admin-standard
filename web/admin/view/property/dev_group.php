<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">プロパティグループの編集</h4>
		</div>
		<form action="property.php?action=dev_group" method="post" enctype="multipart/form-data">
			<?= form_hidden('property_group[id]') ?>
			<div class="modal-body">
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th class="col-3">グループ名</th>
						<td>
							<?= form_error('property_group[name]') ?>
							<?= form_text('property_group[name]', 'class="form-control"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-3">説明文</th>
						<td>
							<?= form_error('property_group[description]') ?>
							<?= form_textarea('property_group[description]', 'class="form-control" rows="3"') ?>
						</td>
					</tr>
					<tr>
						<th class="col-3">表示順序</th>
						<td class="form-inline">
							<?= form_error('property_group[order]') ?>
							<?= form_text('property_group[order]', 'class="form-control" size="5"') ?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存</button>
			</div>
		</form>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
