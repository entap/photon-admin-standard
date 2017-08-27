<?php

/**
 * 管理グループの選択肢を取得する
 *
 * @return array 管理グループの選択肢
 */
function admin_group_get_options()
{
	return db_select_options('admin_group', 'name', 'id');
}

/**
 * 管理グループを取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function admin_group_sql($cond)
{
	sql_clean();
	sql_table('admin_group');
	sql_field('admin_group.*');
	sql_field('(SELECT COUNT(*) FROM admin_user WHERE admin_user.admin_group_id=admin_group.id)', 'admin_user_count');
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['admin_group.name'], $cond['keywords']);
	}
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('admin_group.id', $cond['id']);
	}
	sql_order('admin_group.id', FALSE);
	return sql_select();
}

/**
 * 管理グループを取得する
 *
 * @param integer $id 管理グループのID
 *
 * @return array 管理グループ
 */
function admin_group_get($id)
{
	$admin_group = db_select_row(admin_group_sql(['id' => $id]));
	return $admin_group;
}

/**
 * 管理グループの入力データをバリデーションする
 *
 * @param array $data 入力データ
 *
 * @return array 入力データのフィルタ結果
 */
function admin_group_validate($data)
{
	rule_clean();
	rule('admin_group[id]');
	rule('admin_group[name]', ['required' => 'yes', 'max_chars' => 20]);
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * 管理グループの入力データを保存する
 *
 * @param array  $data   入力データ
 * @param string $action 操作の種類
 *
 * @return integer ID
 */
function admin_group_save($admin_group)
{
	if ($admin_group['id']) {
		db_update_at('admin_group', $admin_group, $admin_group['id']);
		return $admin_group['id'];
	} else {
		return db_insert('admin_group', $admin_group);
	}
}

/**
 * 管理グループが削除可能か？
 *
 * @param array $admin_group 管理グループの役割
 *
 * @return boolean 削除できるか？
 */
function admin_group_deletable($admin_group)
{
	return $admin_group['admin_user_count'] == 0;
}

/**
 * 管理グループを削除する
 *
 * @param integer $id 管理グループのID
 *
 * @return boolean 削除できたか？
 */
function admin_group_delete($id)
{
	$admin_group = admin_group_get($id);
	if (admin_group_deletable($admin_group)) {
		db_delete_at('admin_group', $id);
		return TRUE;
	} else {
		return FALSE;
	}
}

?>