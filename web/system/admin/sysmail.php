<?php

/**
 * メールテンプレートの種類の選択肢を取得する
 *
 * @return array メールテンプレートの種類の選択肢
 */
function sysmail_type_get_options()
{
	return db_select_options('m_sysmail_type', 'name', 'id');
}

/**
 * メールテンプレートの権限を取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function sysmail_sql($cond)
{
	sql_clean();
	sql_table('sysmail');
	sql_field('sysmail.*');
	sql_field('m_sysmail_type.name', 'sysmail_type_name');
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['sysmail.name'], $cond['keywords']);
	}
	if (isset($cond['sysmail_type_id']) && $cond['sysmail_type_id'] !== '') {
		sql_where_search(['sysmail.sysmail_type_id'], $cond['sysmail_type_id']);
	}
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('sysmail.id', $cond['id']);
	}
	sql_join('m_sysmail_type', 'id', 'sysmail', 'sysmail_type_id');
	sql_order('sysmail.sysmail_type_id', TRUE);
	sql_order('sysmail.id', FALSE);
	return sql_select();
}

/**
 * メールテンプレートの権限を取得する
 *
 * @param integer $id メールテンプレートの権限のID
 *
 * @return array メールテンプレートの権限
 */
function sysmail_get($id)
{
	return db_select_row(sysmail_sql(['id' => $id]));
}

/**
 * メールテンプレートの権限の入力データをバリデーションする
 *
 * @param array $data 入力データ
 *
 * @return array 入力データのフィルタ結果
 */
function sysmail_validate($data)
{
	$data['sysmail']['enable_flag'] = intval($data['sysmail']['enable_flag']);
	$data['sysmail']['period_flag'] = intval($data['sysmail']['period_flag']);
	form_date_convert($data, 'sysmail[period_begin]');
	form_date_convert($data, 'sysmail[period_end]');
	rule_clean();
	rule('sysmail[id]');
	rule('sysmail[sysmail_type_id]', ['required' => 'no', 'options' => 'sysmail_type']);
	rule('sysmail[name]', ['required' => 'yes', 'max_chars' => 20]);
	rule('sysmail[note]', ['required' => 'no', 'max_chars' => 1000]);
	rule('sysmail[to]', ['required' => 'yes', 'max_chars' => 1000]);
	rule('sysmail[from]', ['required' => 'yes', 'max_chars' => 100]);
	rule('sysmail[subject]', ['required' => 'yes', 'max_chars' => 100]);
	rule('sysmail[message]', ['required' => 'yes', 'max_chars' => 10000]);
	rule('sysmail[enable_flag]', ['options' => 'boolean']);
	rule('sysmail[period_flag]', ['options' => 'boolean']);
	rule('sysmail[period_begin]', ['required' => $data['sysmail']['period_flag']]);
	rule('sysmail[period_end]', ['required' => $data['sysmail']['period_flag']]);
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * メールテンプレートの権限の入力データを保存する
 *
 * @param array  $data   入力データ
 * @param string $action 操作の種類
 *
 * @return integer ID
 */
function sysmail_save($sysmail)
{
	if ($sysmail['id']) {
		db_update_at('sysmail', $sysmail, $sysmail['id']);
		return $sysmail['id'];
	} else {
		return db_insert('sysmail', $sysmail);
	}
}

/**
 * メールテンプレートの権限を削除する
 *
 * @param integer $id メールテンプレートの権限のID
 *
 * @return boolean 削除できたか？
 */
function sysmail_delete($id)
{
	db_delete_at('sysmail', $id);
	return TRUE;
}

?>
