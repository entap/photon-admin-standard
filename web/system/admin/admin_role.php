<?php

/**
 * 管理ユーザの役割の選択肢を取得する
 *
 * @return array 管理ユーザの役割の選択肢
 */
function admin_role_get_options()
{
	$is_super = admin_has_role('*');
	$is_admin_user_local = admin_group_id_constraint();
	$admin_role_options = [];
	foreach (db_select_table(admin_role_sql([])) as $admin_role) {
		if (auth_id() != 0) {
			$roles = explode(',', $admin_role['roles']);
			if (!$is_super) { // 特権を持たない場合、特権の付与はできない
				if (in_array('*', $roles)) {
					continue;
				}
			}
			if ($is_admin_user_local) { // グループID拘束がある場合、全ユーザ管理権限の付与はできない
				if (in_array('admin_user', $roles)) {
					continue;
				}
			}
		}
		// 選択肢に追加
		$admin_role_options[$admin_role['id']] = $admin_role['name'];
	}
	return $admin_role_options;
}

/**
 * 管理ユーザの役割を取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function admin_role_sql($cond)
{
	sql_clean();
	sql_table('admin_role');
	sql_field('admin_role.*');
	sql_field('(SELECT COUNT(*) FROM admin_user WHERE admin_user.admin_role_id=admin_role.id)', 'admin_user_count');
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['admin_role.name'], $cond['keywords']);
	}
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('admin_role.id', $cond['id']);
	}
	sql_order('admin_role.id', FALSE);
	return sql_select();
}

/**
 * 管理ユーザの役割を取得する
 *
 * @param integer $id 管理ユーザの役割のID
 *
 * @return array 管理ユーザの役割
 */
function admin_role_get($id)
{
	$admin_role = db_select_row(admin_role_sql(['id' => $id]));
	$admin_role['roles'] = explode(',', $admin_role['roles']);
	return $admin_role;
}

/**
 * 管理ユーザの役割の入力データをバリデーションする
 *
 * @param array $data 入力データ
 *
 * @return array 入力データのフィルタ結果
 */
function admin_role_validate($data)
{
	rule_clean();
	rule('admin_role[id]');
	rule('admin_role[name]', ['required' => 'yes', 'max_chars' => 20]);
	rule('admin_role[roles]');
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * 管理ユーザの役割の入力データを保存する
 *
 * @param array $admin_role 管理ユーザの役割
 *
 * @return integer ID
 */
function admin_role_save($admin_role)
{
	if (is_array($admin_role['roles'])) {
		$admin_role['roles'] = implode(',', $admin_role['roles']);
	}
	if ($admin_role['id']) {
		db_update_at('admin_role', $admin_role, $admin_role['id']);
		return $admin_role['id'];
	} else {
		return db_insert('admin_role', $admin_role);
	}
}

/**
 * 管理ユーザの役割が削除可能か？
 *
 * @param array $admin_role 管理ユーザの役割
 *
 * @return boolean 削除できるか？
 */
function admin_role_deletable($admin_role)
{
	return $admin_role['admin_user_count'] == 0;
}

/**
 * 管理ユーザの役割を削除する
 *
 * @param integer $id 管理ユーザの役割のID
 *
 * @return boolean 削除できたか？
 */
function admin_role_delete($id)
{
	$admin_role = admin_role_get($id);
	if (admin_role_deletable($admin_role)) {
		db_delete_at('admin_role', $id);
		return TRUE;
	} else {
		return FALSE;
	}
}

?>