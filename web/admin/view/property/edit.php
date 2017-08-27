<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><?= h($property['name']) ?></h4>
		</div>
		<form action="property.php?action=edit" method="post" enctype="multipart/form-data">
			<?= form_hidden('id') ?>
			<div class="modal-body">
				<?= form_error('value') ?>
				<?= property_value_form('value', $property) ?>
				<p><?= h($property['description']) ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存</button>
			</div>
		</form>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
