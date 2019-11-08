op.api = _.extend({
	apiKey: null,
	apiBase: null,

	getJSON: function(url, data, callback) {
		if ($.isFunction(data)) {
			callback = data;
			data = undefined;
		}
		this.ajax({
			type: 'GET',
			url: url,
			data: data,
			dataType: 'json',
			success: callback
		});
	},

	ajax: function(url, options) {
		if (typeof url === "object") {
			options = url;
			url = undefined;
		}
		$.ajax(url, _.extend({}, options, {
			url: this.apiBase + options.url,
			data: _.extend({apiKey: this.apiKey}, options.data)
		}));
	}

}, op.api);
