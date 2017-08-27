/**
 * ダイナミックに表示・非表示を切り替える
 *
 * - data-vifを持つ要素について、動的に表示を切り替える。
 * - data-vifで指定したフォームの入力値が、data-vif-valueで指定した条件値と一致したら表示します。
 * - data-vif-valueが省略された場合、条件値は"1"となります。
 */
$(function () {
	var update = function (name, $e) {
		var vif_value = $e.attr('data-vif-value');
		vif_value = (vif_value == undefined) ? "1" : vif_value;
		var visible = false;
		$.each($e.closest('form').serializeArray(), function (i, field) {
			visible |= (field.name == name && field.value == vif_value);
		});
		visible ? $e.show() : $e.hide();
	}
	$('[data-vif]').each(function () {
		var $e = $(this);
		var name = $e.attr('data-vif');
		update(name, $(this));
		$e.closest('form').on('change click keyup paste', '[name="' + name + '"]', function () {
			update(name, $e);
		});
	});
});