<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">パスワード変更</h4>
		</div>
		<form action="admin_user.php?action=password" method="post" enctype="multipart/form-data">
			<?= form_hidden('admin_user[id]') ?>
			<div class="modal-body">
				<table class="table table-bordered">
					<tbody>
					<tr>
						<th class="col-3">パスワード</th>
						<td>
							<?= form_error('admin_user[password]') ?>
							<?= form_password('admin_user[password]', 'class="form-control"') ?>
						</td>
					</tr>
					<tr>
						<th>パスワード(確認)</th>
						<td>
							<?= form_error('admin_user[password_confirm]') ?>
							<?= form_password('admin_user[password_confirm]', 'class="form-control"') ?>
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
