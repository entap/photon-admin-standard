<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';

// ログテーブルの一覧を取得
function _log_tables()
{
	return db_select_column('SHOW TABLE STATUS LIKE \'log\_%\'', 'Comment', 'Name');
}

// ログテーブルのフィールド名を取得
function _log_fields($table)
{
	return db_select_column('SHOW FULL COLUMNS FROM ' . db_quote_field($table), 'Comment', 'Field');
}

/**
 * ログの閲覧
 */
function action_index($data)
{
	// ログテーブルが正しく指定されているかチェック
	$data['tables'] = _log_tables();
	if (isset($data['c']['table'])) {
		if (!isset($data['tables'][$data['c']['table']])) {
			unset($data['c']['table']);
		}
	}

	// 開始期間
	if (isset($data['c']['created_min'])) {
		form_date_convert($data, 'c[created_min]');
	} else {
		$data['c']['created_min'] = date('Y-m-d', time() - 7 * 24 * 60 * 60);
	}

	// 終了期間
	if (isset($data['c']['created_max'])) {
		form_date_convert($data, 'c[created_max]');
	} else {
		$data['c']['created_max'] = date('Y-m-d');
	}

	if (isset($data['c']['table'])) {
		// フィールド名
		$data['fields'] = _log_fields($data['c']['table']);
		unset($data['fields']['id']);

		// 検索
		sql_clean();
		sql_table($data['c']['table']);
		sql_where_string('created', $data['c']['created_min'] . ' 00:00:00', '>=');
		sql_where_string('created', $data['c']['created_max'] . ' 23:59:59', '<=');
		if (strval($data['c']['keywords']) !== '') {
			sql_where_search(array_keys($data['fields']), $data['c']['keywords']);
		}
		sql_order('id', FALSE);
		$data['data'] = db_paginate(sql_select());
	}

	// 表示
	form_set_value('c', $data['c']);
	render('view/log/index.php', $data);
}

admin_bootstrap(TRUE);
admin_has_role('log') or die;
execute();
?>