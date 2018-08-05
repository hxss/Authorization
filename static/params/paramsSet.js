
Element.prototype.ParamsSet = function(settings) {
	return new ParamsSet(this, settings);
};
$.fn.paramsSet = function(settings) {
	return $(this).map(function() {
		return this.ParamsSet(settings);
	});
};

function ParamsSet(node, settings) {
	if (node.paramsSet)
		return node.paramsSet;

	this.node = null;

	this.settings = Object.assign({
		url: '',
		paramsList: '.premission__param__list',
	}, settings);

	this.lists = {};
	this.form = null;

	this.init = function(node, settings) {
		this.node = $(node);
		this.node.paramsSet = this;
		this.initLists();
		this.form = this.node.closest('form');

		return this;
	};

	this.initLists = function() {
		this.node.find(this.settings.paramsList)
			.paramsList({
				paramsSet: this,
			}).toArray().forEach(function(list) {
				this.lists[list.name] = list;
			}, this);
	};

	this.update = function(list) {
		var data = this.form.serializeArray();
		data.push({
			name: 'param',
			value: list.name,
		});
		var _this = this;
		$.post(this.settings.url, data, function(data, status) {
			Object.keys(data.content).forEach(function(key) {
				if (list = _this.lists[key]) {
					list.getItems().each(function() {
						$(this).parent().remove();
					});
					list.content.append(data.content[key]);
					list.static().toggle();
				}
			});
		})
	};

	return this.init(node, settings);
}
