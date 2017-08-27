<?php

/**
 * プロパティの型の選択肢を取得する
 *
 * @return array プロパティの型の選択肢
 */
function property_type_get_options()
{
	return db_select_options('m_property_type', 'name', 'id');
}

/**
 * プロパティを取得するSQLを生成する
 *
 * @param array $cond 検索条件
 *
 * @return string SQL
 */
function property_sql($cond)
{
	sql_clean();
	sql_table('property');
	sql_field('property.*');
	sql_field('m_property_type.cd', 'property_type_cd');
	sql_field('COALESCE(property_value.value, property.default_value)', 'value');
	sql_field('property_value.value IS NULL', 'default');
	if (isset($cond['keywords']) && $cond['keywords'] !== '') {
		sql_where_search(['property.cd', 'property.name'], $cond['keywords']);
	}
	if (isset($cond['property_group_id']) && $cond['property_group_id'] !== '') {
		sql_where_integer('property.property_group_id', $cond['property_group_id']);
	}
	if (isset($cond['id']) && $cond['id'] !== '') {
		sql_where_integer('property.id', $cond['id']);
	}
	if (isset($cond['cd']) && $cond['cd'] !== '') {
		sql_where_integer('property.cd', $cond['cd']);
	}
	sql_join('m_property_type', 'id', 'property', 'property_type_id');
	sql_join('property_value', 'property_cd', 'property', 'cd');
	sql_join('property_group', 'id', 'property', 'property_group_id');
	sql_order('property_group.order');
	sql_order('property.order');
	return sql_select();
}

/**
 * プロパティを取得する
 *
 * @param integer $id プロパティのID
 *
 * @return array プロパティ
 */
function property_get($id)
{
	return db_select_row(property_sql(['id' => $id]));
}

/**
 * プロパティのスキーマをバリデーションする
 *
 * @param array  $data   入力データ
 * @param string $action 操作の種類 (edit / import)
 *
 * @return array 入力データのフィルタ結果
 */
function property_validate($data, $action)
{
	if ($action == 'edit') {
		// プロパティの識別子の重複チェック
		$d = db_select_at('property', $data['property']['cd'], 'cd');
		if ($d !== NULL && $d['id'] != $data['property']['id']) {
			form_set_error('property[cd]', 'この識別子は既に使用されています');
		}
	}
	rule_clean();
	rule('property[id]');
	if ($action == 'edit') {
		rule('property[property_group_id]', ['required' => 'yes', 'option' => 'property_group']);
	}
	rule('property[name]', ['required' => 'yes', 'max_chars' => 20]);
	rule('property[description]', ['required' => 'no', 'max_chars' => 10000]);
	rule('property[cd]', ['required' => 'yes', 'type' => 'alnum', 'max_chars' => 20]);
	rule('property[property_type_id]', ['required' => 'yes', 'option' => 'property_type']);
	rule('property[default_value]', property_value_rule($data['property']));
	rule('property[order]', ['required' => 'yes', 'type' => 'integer']);
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * プロパティのスキーマを保存する
 *
 * @param array $property プロパティ
 *
 * @return integer ID
 */
function property_save($property)
{
	if ($property['id']) {
		db_update_at('property', $property, $property['id']);
		return $property['id'];
	} else {
		return db_insert('property', $property);
	}
}

/**
 * プロパティのスキーマを削除する
 *
 * @param integer $id プロパティグループのID
 *
 * @return boolean 削除できたか？
 */
function property_delete($id)
{
	db_delete_at('property', $id);
	return TRUE;
}

/**
 * プロパティのスキーマをインポートする
 *
 * @param string $json インポートするJSON文字列
 */
function property_import($json)
{
	$data = json_decode($json, TRUE);

	// バリデーション
	if (empty($data) && !is_array($data)) {
		form_set_error('file', 'ファイルが読み込めませんでした');
		return FALSE;
	}
	foreach ($data as $property_group) {
		if (!property_group_validate(['property_group' => $property_group])) {
			form_set_error('file', 'グループの記述が不正です: ' . $property_group['name']);
			return FALSE;
		}
		foreach ($property_group['properties'] as $property) {
			if (!property_validate(['property' => $property], 'import')['property']) {
				form_set_error('file', 'プロパティの記述が不正です: ' . $property['cd']);
				return FALSE;
			}
		}
	}

	// 実行
	db_table_drop(['property_new', 'property_group_new', 'property_old', 'property_group_old']);
	db_table_copy('property', 'property_new', FALSE);
	db_table_copy('property_group', 'property_group_new', FALSE);
	foreach ($data as $group) {
		$group_id = db_insert('property_group_new', $group);
		foreach ($group['properties'] as $property) {
			$property['property_group_id'] = $group_id;
			db_insert('property_new', $property);
		}
	}
	db_table_rename('property', 'property_old');
	db_table_rename('property_new', 'property');
	db_table_rename('property_group', 'property_group_old');
	db_table_rename('property_group_new', 'property_group');
	db_table_drop(['property_old', 'property_group_old']);
	return TRUE;
}

/**
 * プロパティのスキーマを連想配列としてエクスポートする
 *
 * @return string エクスポート結果のJSON文字列
 */
function property_export()
{
	$data = [];
	foreach (db_select_table(property_group_sql([])) as $group) {
		$g = [
			'name'        => $group['name'],
			'description' => $group['description'],
			'order'       => $group['order'],
			'properties'  => [],
		];
		foreach (db_select_table(property_sql(['property_group_id' => $group['id']])) as $property) {
			$g['properties'][] = [
				'name'             => $property['name'],
				'description'      => $property['description'],
				'cd'               => $property['cd'],
				'property_type_id' => $property['property_type_id'],
				'default_value'    => $property['default_value'],
				'order'            => $property['order'],
			];
		}
		$data[] = $g;
	}
	return json_encode($data);
}

/**
 * プロパティの設定値のバリデーションルールを取得する
 *
 * @param array $property プロパティ
 *
 * @return array 入力ルール
 */
function property_value_rule($property)
{
	if (!isset($property['property_type_cd']) && isset($property['property_type_id'])) {
		$property['property_type_cd'] = db_select_at('m_property_type', $property['property_type_id'])['cd'];
	}
	switch ($property['property_type_cd']) {
		case 'integer':
			return ['type' => 'integer'];
		case 'decimal':
			return ['type' => 'decimal'];
		case 'string':
			return [];
		case 'text':
			return [];
		case 'boolean':
			return ['options' => 'boolean'];
	}
}

/**
 * プロパティの設定値をバリデーションする
 *
 * @param array $data     入力データ
 * @param array $property プロパティ
 *
 * @return array 入力データのフィルタ結果
 */
function property_value_validate($data, $property)
{
	rule_clean();
	rule('id');
	rule('value', property_value_rule($property));
	$data = filter($data);
	validate($data);
	return $data;
}

/**
 * プロパティの設定値を保存する
 *
 * @param array $property_value プロパティの設定値(property_cd, value)
 *
 * @return integer ID
 */
function property_value_save($property_value)
{
	db_delete_at('property_value', $property_value['property_cd'], 'property_cd');
	db_insert('property_value', $property_value, $property_value['property_cd']);
}

/**
 * プロパティの設定値の入力フォームを生成する
 *
 * @param string $name     フォームの名前
 * @param array  $property プロパティ
 *
 * @return array 入力ルール
 */
function property_value_form($name, $property)
{
	switch ($property['property_type_cd']) {
		case 'integer':
			return form_text($name, 'class="form-control"');
		case 'decimal':
			return form_text($name, 'class="form-control"');
		case 'string':
			return form_text($name, 'class="form-control"');
		case 'text':
			return form_textarea($name, 'class="form-control" rows="5"');
		case 'boolean':
			return form_select_assoc($name, 'boolean', 'class="form-control"');
	}
}

/**
 * プロパティの設定値をインポートする
 *
 * @param array $csv インポートするCSVデータ
 */
function property_value_import($csv)
{
	$data = csv_to_array($csv, ['cd', 'default', 'value']);
	if (!$data) {
		form_set_error('file', 'ファイルが読み込めませんでした');
		return FALSE;
	}

	// チェック処理
	foreach ($data as $row) {
		if ($row['cd'] !== '') {
			if ($property = db_select_row(property_sql(['cd' => $row['cd']]))) {
				if (!$row['default']) {
					if (!property_value_validate(['value' => $row['value']], $property)) {
						form_set_error('file', '記述に不正があります:' . $row['cd']);
						return FALSE;
					}
				}
			} else {
				form_set_error('file', 'cdが不正です:' . $row['cd']);
				return FALSE;
			}
		}
	}

	// 実行
	foreach ($data as $row) {
		if ($row['cd'] !== '') {
			if ($row['default']) {
				db_delete_at('property_value', $row['cd'], 'cd');
			} else {
				db_replace('property_value', ['property_cd' => $row['cd'], 'value' => $row['value']]);
			}
		}
	}
	return TRUE;
}

/**
 * プロパティの設定値をCSVとしてエクスポートする
 *
 * @return string エクスポート結果のCSV文字列
 */
function property_value_export()
{
	return array_to_csv(db_select_table(property_sql([])), ['cd', 'default', 'value']);
}

?>