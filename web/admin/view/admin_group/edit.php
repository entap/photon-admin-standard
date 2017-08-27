<?php require_once __DIR__ . '/../common/header.php' ?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">管理グループ</h4>
			</div>
			<form action="admin_group.php?action=edit" method="post" enctype="multipart/form-data">
				<?= form_hidden('admin_group[id]') ?>
				<div class="modal-body">
					<table class="table table-bordered">
						<tbody>
						<tr>
							<th class="col-xs-3">名前</th>
							<td>
								<?= form_error('admin_group[name]') ?>
								<?= form_text('admin_group[name]', 'class="form-control" size="40"') ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存
					</button>
				</div>
			</form>
		</div>
	</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>