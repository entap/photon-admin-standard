<?php

/**
 * プロパティの値を取得する
 *
 * @param string $cd            プロパティの識別子
 * @param mixed  $default_value デフォルト値
 *
 * @return string プロパティの値
 */
function prop($cd, $default_value = NULL)
{
	global $__prop;
	if (!isset($__prop)) {
		$sql = 'SELECT COALESCE(property_value.value,property.default_value) AS value,property.cd FROM property';
		$sql .= ' LEFT JOIN property_value ON property_value.property_cd=property.cd';
		$__prop = db_select_column($sql, 'value', 'cd');
	}
	return isset($__prop[$cd]) ? $__prop[$cd] : $default_value;
}

/**
 * メールを送信する
 *
 * @param string $type メールテンプレートの種類
 * @param array  $data 埋め込むデータ
 */
function sysmail($type, $data)
{
	// 有効なメールテンプレートを取得する
	$now = db_datetime();
	sql_push();
	sql_clean();
	sql_table('sysmail');
	sql_field('sysmail.to');
	sql_field('sysmail.from');
	sql_field('sysmail.subject');
	sql_field('sysmail.message');
	sql_where_integer('enable_flag', 1);
	sql_where_begin('OR');
	sql_where_integer('period_flag', 0);
	sql_where_begin('AND');
	sql_where_string('period_begin', $now, '<=');
	sql_where_string('period_end', $now, '>=');
	sql_where_end();
	sql_where_end();
	sql_where_string('m_sysmail_type.cd', $type);
	sql_join('m_sysmail_type', 'id', 'sysmail', 'sysmail_type_id');
	$sysmails = db_select_table(sql_select());
	sql_pop();

	// メール送信する
	foreach ($sysmails as $sysmail) {
		// 変数を埋め込む
		foreach (['to', 'from', 'subject', 'messsage'] as $key) {
			$sysmail[$key] = embed($sysmail[$key], $data);
		}
		// 指定された宛先に対し、メールを送信する
		foreach (explode(';', $sysmail['to']) as $to) {
			if (($to = trim($to)) !== '') {
				sendmail($to, $sysmail['from'], $sysmail['subject'], $sysmail['message']);
			}
		}
	}
}

?>