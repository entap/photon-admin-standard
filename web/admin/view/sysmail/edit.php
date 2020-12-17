<?php require_once __DIR__ . '/../common/header.php' ?>
	<div class="page-header">
		<h1>メールテンプレート</h1>
	</div>
	<div class="well well-sm">
		<div>・メールテンプレートの送信元、宛先、題名、本文には、{変数名}の形式で変数を埋め込みできます。</div>
		<div>・宛先はセミコロンで区切ることで複数のアドレスを指定ができます。</div>
	</div>
	<p>
		<a href="sysmail.php?action=index" class="btn btn-info"><span class="glyphicon glyphicon-arrow-left"></span> 一覧に戻る</a>
	</p>
	<form action="sysmail.php?action=edit" method="post" enctype="multipart/form-data">
		<?= form_hidden('sysmail[id]') ?>
		<table class="table table-bordered">
			<tbody>
			<tr>
				<th class="col-3">管理用の名前</th>
				<td>
					<?= form_error('sysmail[name]') ?>
					<?= form_text('sysmail[name]', 'class="form-control"') ?>
				</td>
			</tr>
			<tr>
				<th>管理用のメモ</th>
				<td>
					<?= form_error('sysmail[note]') ?>
					<?= form_textarea('sysmail[note]', 'class="form-control" rows="3"') ?>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-bordered">
			<tbody>
			<tr>
				<th class="col-3">種類</th>
				<td class="form-inline">
					<?= form_error('sysmail[sysmail_type_id]') ?>
					<?= form_select_assoc('sysmail[sysmail_type_id]', 'sysmail_type', 'class="form-control" blank=""') ?>
				</td>
			</tr>
			<tr>
				<th>送信元</th>
				<td>
					<?= form_error('sysmail[from]') ?>
					<?= form_text('sysmail[from]', 'class="form-control"') ?>
				</td>
			</tr>
			<tr>
				<th>宛先</th>
				<td>
					<?= form_error('sysmail[to]') ?>
					<?= form_text('sysmail[to]', 'class="form-control"') ?>
				</td>
			</tr>
			<tr>
				<th>題名</th>
				<td>
					<?= form_error('sysmail[subject]') ?>
					<?= form_text('sysmail[subject]', 'class="form-control"') ?>
				</td>
			</tr>
			<tr>
				<th>本文</th>
				<td>
					<?= form_error('sysmail[message]') ?>
					<?= form_textarea('sysmail[message]', 'class="form-control" rows="10"') ?>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-bordered form-inline">
			<tbody>
			<tr>
				<th class="col-3">有効</th>
				<td>
					<?= form_error('sysmail[enable_flag]') ?>
					<?= form_select_assoc('sysmail[enable_flag]', 'boolean', 'class="form-control"') ?>
				</td>
			</tr>
			<tr>
				<th>有効期間</th>
				<td>
					<?= form_error('sysmail[period_flag]') ?>
					<?= form_select_assoc('sysmail[period_flag]', 'boolean', 'class="form-control"') ?>
					<div style="margin-top: 5px;" data-vif="sysmail[period_flag]">
						<div><label>開始日時</label></div>
						<p><?= form_date('sysmail[period_begin]', '{y} 年 {m} 月 {d} 日 {h} 時 {i} 分', 'class="form-control"') ?></p>
						<div><label>終了日時</label></div>
						<p><?= form_date('sysmail[period_end]', '{y} 年 {m} 月 {d} 日 {h} 時 {i} 分', 'class="form-control"') ?></p>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="text-center">
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> 保存</button>
		</div>
	</form>
<?php require_once __DIR__ . '/../common/footer.php' ?>