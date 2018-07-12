<?php

/// 管理グループの階層制限
define('ADMIN_GROUP_MAX_DEPTH', 8);

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
 * 管理グループの親の選択肢を取得する
 *
 * @return array 管理グループの選択肢
 */
function admin_group_parent_get_options($admin_group_id)
{
	if ($admin_group_id == 0) {
		return admin_group_get_options();
	} else {
		admin_group_sql(['exclude_path' => admin_group_get($admin_group_id)['path']]);
		return db_select_column(sql_select(), 'name', 'id');
	}
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
	if (isset($cond['ancestor_path']) && $cond['ancestor_path'] !== '') {
		sql_where('admin_group.path LIKE \'' . $cond['ancestor_path'] . '%\'');
	}
	if (isset($cond['exclude_path']) && $cond['exclude_path'] !== '') {
		sql_where('admin_group.path NOT LIKE \'' . $cond['exclude_path'] . '%\'');
	}
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['admin_group.name'], $cond['keywords']);
	}
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('admin_group.id', $cond['id']);
	}
	sql_order('admin_group.path', TRUE);
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
	rule('admin_group[parent_id]', ['required' => 'no', 'options' => 'admin_group']);
	rule('admin_group[name]', ['required' => 'yes', 'max_chars' => 20]);
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * 管理グループの入力データを保存する
 *
 * @param array $data 入力データ
 *
 * @return integer ID
 */
function admin_group_save($admin_group)
{
	if ($admin_group['id']) {
		db_update_at('admin_group', $admin_group, $admin_group['id']);
	} else {
		$admin_group['id'] = db_insert('admin_group', $admin_group);
	}
	admin_group_path_update($admin_group['id']);
	return $admin_group['id'];
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

/**
 * 管理グループのパスを生成する
 *
 * @param integer $id 管理グループのID
 * @param integer $parent_id 管理グループの親ID
 *
 * @return string パス
 */
function admin_group_path($id, $parent_id)
{
	$id_hex = sprintf('%x', $id);
	if ($parent_id == 0) {
		return '/' . $id_hex;
	} else {
		$parent = admin_group_get($parent_id);
		return $parent['path'] . '/' . $id_hex;
	}
}

/**
 * 管理グループの子孫のパスを再帰的に更新する
 *
 * @param integer $id 管理グループのID
 */
function admin_group_path_update($id)
{
	admin_group_sql(['ancestor_path' => admin_group_get($id)['path']]);
	foreach (db_select_table(sql_select()) as $admin_group) {
		$path = admin_group_path($admin_group['id'], $admin_group['parent_id']);
		db_update_at('admin_group', ['path' => $path], $admin_group['id']);
	}
}

/**
 * 管理グループの階層数を取得する
 *
 * @param integer $admin_group 管理グループ
 *
 * @return integer 階層数
 */
function admin_group_depth($admin_group)
{
	return substr_count($admin_group['path'], '/') - 1;
}

?>