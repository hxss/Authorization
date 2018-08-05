
Element.prototype.ParamsList = function(settings) {
	return new ParamsList(this, settings);
};
$.fn.paramsList = function(settings) {
	return $(this).map(function() {
		return this.ParamsList(settings);
	});
};

function ParamsList(node, settings) {
	if (node.paramsList)
		return node.paramsList;

	this.node = null;

	this.settings = Object.assign({
		upgradeLink: '',
		content: '.premission__param__list__content',
		items: ':checkbox:not([data-all-toggle])',
		label: '.label',
		summary: '.premission__param__list__summary .label',
		toggler: '[data-all-toggle]',
		allUnchecked: 'allUnchecked',
		paramsSet: null,
	}, settings);

	this.name = '';
	this.summary = null;
	this.content = null;
	this.toggler = null;
	this.paramsSet = null;

	this._static = false;
	this.handler = {};

	this.init = function(node, settings) {
		this.node = $(node);
		this.node.paramsList = this;
		this.summary = this.node.find(this.settings.summary);
		this.content = this.node.find(this.settings.content);
		this.name = this.content[0].dataset.paramName;
		this.paramsSet = this.settings.paramsSet;

		this.initToggler();
		this.initItems();

		this.static().toggle();

		return this;
	};

	this.initToggler = function() {
		this.toggler = this.node.find(this.settings.toggler);

		this.toggler[0].paramsList = this;

		var _this = this;
		this.handler.toggleAll = function() {
			_this.toggleAll();
		};
		this.toggler.change(this.handler.toggleAll);
	}

	this.initItems = function() {
		var _this = this;
		this.handler.toggle = function() {
			_this.toggle(this);
		};
		this.node.on('change', this.settings.items, this.handler.toggle);
	}

	this.static = function() {
		this._static = true;

		return this;
	};

	this.toggle = function(element) {
		var allItems = this.getItems();
		var checked  = allItems.filter(':checked');

		this.toggler[0].checked = (allItems.length == checked.length);
		this.update(checked.length == 0);
	}

	this.toggleAll = function() {
		this.getItems().prop({checked: this.toggler[0].checked});
		this.update(!this.toggler[0].checked);
	}

	this.update = function(uncheckAll) {
		var labels = this.node.find(this.settings.items + ':checked + ' + this.settings.label);
		var text = labels.map(function() {
			return this.textContent;
		}).toArray().join(', ') || '*';

		this.summary.text(text);

		this.uncheckAll(uncheckAll);

		if (!this._static && this.paramsSet) {
			this.paramsSet.update(this);
		}
		this._static = false;
	}

	this.uncheckAll = function(uncheck) {
		if (uncheck) {
			this.node.addClass(this.settings.allUnchecked);
		} else {
			this.node.removeClass(this.settings.allUnchecked);
		}
	}

	this.getItems = function() {
		return this.node.find(this.settings.items);
	}

	return this.init(node, settings);
}
