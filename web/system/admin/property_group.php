<?php

/**
 * プロパティのグループを取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function property_group_sql($cond)
{
	sql_clean();
	sql_table('property_group');
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('property_group.id', $cond['id']);
	}
	sql_order('property_group.order');
	return sql_select();
}

/**
 * プロパティのグループを取得する
 *
 * @param integer $id プロパティのグループのID
 *
 * @return array プロパティのグループ
 */
function property_group_get($id)
{
	return db_select_row(property_group_sql(['id' => $id]));
}

/**
 * プロパティのグループをバリデーションする
 *
 * @param array $data 入力データ
 *
 * @return array 入力データのフィルタ結果
 */
function property_group_validate($data)
{
	rule_clean();
	rule('property_group[id]');
	rule('property_group[name]', ['required' => 'yes', 'max_chars' => 20]);
	rule('property_group[description]', ['required' => 'no', 'max_chars' => 10000]);
	rule('property_group[order]', ['required' => 'yes', 'type' => 'integer']);
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * プロパティのグループを保存する
 *
 * @param array $property_group プロパティのグループ
 *
 * @return integer ID
 */
function property_group_save($property_group)
{
	if ($property_group['id']) {
		db_update_at('property_group', $property_group, $property_group['id']);
		return $property_group['id'];
	} else {
		return db_insert('property_group', $property_group);
	}
}

/**
 * プロパティグループを削除する
 *
 * @param integer $id プロパティグループのID
 *
 * @return boolean 削除できたか？
 */
function property_group_delete($id)
{
	db_delete_at('property_group', $id);
	db_delete_at('property', $id, 'property_group_id');
	return TRUE;
}

?>