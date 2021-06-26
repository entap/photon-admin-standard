<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">スキーマの読込</h4>
		</div>
		<form action="property.php?action=dev_import" method="post" enctype="multipart/form-data">
			<div class="modal-body">
				<?= form_file('file') ?>
				<?= form_error('file') ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 実行</button>
			</div>
		</form>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
