<?php

require_once __DIR__ . '/../system/photon/photon.php';
require_once __DIR__ . '/../system/admin/admin.php';
require_once __DIR__ . '/../system/admin/property.php';
require_once __DIR__ . '/../system/admin/property_group.php';

/**
 * プロパティの一覧
 */
function action_index($data)
{
	$data['property_groups'] = db_select_table(property_group_sql([]));
	if (!isset($data['c'])) {
		$data['c'] = [];
	}
	if (!isset($data['c']['property_group_id'])) {
		$data['c']['property_group_id'] = (count($data['property_groups']) > 0) ? $data['property_groups'][0]['id'] : 0;
	}
	$data['property_group'] = db_select_row(property_group_sql(['id' => $data['c']['property_group_id']]));
	$data['properties'] = db_select_table(property_sql($data['c']));
	form_set_value('c', $data['c']);
	render('view/property/index.php', $data);
}

/**
 * プロパティの設定値の編集
 */
function action_edit($data)
{
	isset($data['id']) or die;
	$property = property_get($data['id']);
	if (is_request_post()) {
		$data = property_value_validate($data, $property);
		if (!form_has_error()) {
			property_value_save(['property_cd' => $property['cd'], 'value' => $data['value']]);
			return redirect('property.php');
		}
	} else {
		$data['value'] = $property['value'];
	}
	$data['property'] = $property;
	form_set_value(NULL, $data);
	render('view/property/edit.php', $data);
}

/**
 * プロパティの設定値のインポート
 */
function action_import($data)
{
	ini_set('display_errors', 1);
	if (is_request_post()) {
		$content = @file_get_contents($_FILES['file']['tmp_name']);
		if (property_value_import($content)) {
			return redirect('property.php');
		}
	}
	form_set_value(NULL, $data);
	render('view/property/import.php', $data);
}

/**
 * プロパティの設定値のエクスポート
 */
function action_export($data)
{
	download('property.csv', property_value_export());
}

/**
 * [開発者モード] 編集
 */
function action_dev_edit($data)
{
	admin_has_role('*') or die;
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = property_validate($data, 'edit');
		if (!form_has_error()) {
			property_save($data['property']);
			return redirect('property.php?dev=1');
		}
	} else if ($data['id']) {
		$data['property'] = property_get($data['id']);
	} else {
		$data['property'] = [
			'property_group_id' => $data['property_group_id'],
		];
	}
	form_set_value(NULL, $data);
	render('view/property/dev_edit.php', $data);
}

/**
 * [開発者モード] プロパティの削除
 */
function action_dev_delete($data)
{
	admin_has_role('*') or die;
	property_delete($data['id']) or die;
	redirect('property.php?dev=1');
}

/**
 * [開発者モード] プロパティのグループの編集
 */
function action_dev_group($data)
{
	admin_has_role('*') or die;
	$data['id'] = isset($data['id']) ? intval($data['id']) : 0;
	if (is_request_post()) {
		$data = property_group_validate($data);
		if (!form_has_error()) {
			property_group_save($data['property_group']);
			return redirect('property.php?dev=1');
		}
	} else if ($data['id']) {
		$data['property_group'] = property_group_get($data['id']);
	} else {
		$data['property_group'] = [];
	}
	form_set_value(NULL, $data);
	render('view/property/dev_group.php', $data);
}

/**
 * [開発者モード] プロパティのグループの削除
 */
function action_dev_group_delete($data)
{
	admin_has_role('*') or die;
	property_group_delete($data['id']) or die;
	redirect('property.php?dev=1');
}

/**
 * [開発者モード] プロパティのインポート
 */
function action_dev_import($data)
{
	if (is_request_post()) {
		$content = @file_get_contents($_FILES['file']['tmp_name']);
		if (property_import($content)) {
			return redirect('property.php?dev=1');
		}
	}
	form_set_value(NULL, $data);
	render('view/property/dev_import.php', $data);
}

/**
 * [開発者モード] プロパティのエクスポート
 */
function action_dev_export($data)
{
	download('property.json', property_export());
}

admin_bootstrap(TRUE);
admin_has_role('property') or die;
execute();
?>