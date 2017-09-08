<?php

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
				db_delete_at('property_value', $row['cd'], 'property_cd');
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