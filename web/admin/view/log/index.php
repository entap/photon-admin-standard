<?php require_once __DIR__ . '/../common/header.php' ?>
<div class="row">
	<div class="col-xs-3">
		<form action="log.php">
			<div class="form-group">
				<label>対象のログ</label>
				<?= form_select_assoc('c[table]', _log_tables(), 'class="form-control" blank=""') ?>
			</div>
			<div class="form-group">
				<label>開始期間</label>
				<div class="form-inline"><?= form_date('c[created_min]', '{y} 年 {m} 月 {d} 日', 'class="form-control"') ?></div>
			</div>
			<div class="form-group">
				<label>終了期間</label>
				<div class="form-inline"><?= form_date('c[created_max]', '{y} 年 {m} 月 {d} 日', 'class="form-control"') ?></div>
			</div>
			<div class="form-group">
				<label>キーワード</label>
				<?= form_text('c[keywords]', 'class="form-control"') ?>
			</div>
			<button class="btn btn-primary btn-block"><span class="glyphicon glyphicon-search"></span> 検索</button>
		</form>
	</div>
	<div class="col-xs-9">
		<?php if (isset($c['table'])) { ?>
			<div class="page-header">
				<h1><?= form_static_assoc('c[table]', $tables) ?></h1>
			</div>
			<?php foreach ($data['records'] as $record) { ?>
				<table class="table table-bordered">
					<tbody>
					<?php foreach ($fields as $field => $name) { ?>
						<tr>
							<th class="col-xs-3"><?= h($name) ?></th>
							<td class="col-xs-9">
								<div style="overflow-wrap: break-word; overflow-x: scroll;"><?= h($record[$field]) ?></div>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			<?= $data['html'] ?>
		<?php } else { ?>
			<div class="page-header">
				<h1>ログ</h1>
			</div>
			<p>ログを選択してください。</p>
		<?php } ?>
	</div>
</div>
<?php require_once __DIR__ . '/../common/footer.php' ?>
