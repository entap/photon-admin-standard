<?php require_once __DIR__ . '/../common/header.php' ?>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">役割</h4>
			</div>
			<form action="admin_role.php?action=edit" method="post" enctype="multipart/form-data">
				<?= form_hidden('admin_role[id]') ?>
				<div class="modal-body">
					<table class="table table-bordered">
						<tbody>
						<tr>
							<th class="col-3">名前</th>
							<td class="col-9">
								<?= form_error('admin_role[name]') ?>
								<?= form_text('admin_role[name]', 'class="form-control" size="40"') ?>
							</td>
						</tr>
						<tr>
							<th>役割</th>
							<td>
								<?= form_error('admin_role[roles]') ?>
								<?php foreach (db_select('m_admin_role') as $role) { ?>
									<div class="checkbox"><?= form_checkbox('admin_role[roles][]', $role['cd'], $role['name']) ?></div>
								<?php } ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存
					</button>
				</div>
			</form>
		</div>
	</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>