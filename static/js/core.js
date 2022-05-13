var itemList = {
	item: null,
	list: null,

	ajax: function(method, key) {
		let url = window.location.origin + '/api/redis';
		if (key)
			url += '/' + key;
		var self = this;
		let xhr = new XMLHttpRequest();
		xhr.open(method, url);
		xhr.setRequestHeader('X-AJAX', '1');
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
				if (xhr.status == 200)
					self.modify(method, JSON.parse(xhr.responseText));
			}
		}
		xhr.send();
	},

	click: function(event) {
		event.preventDefault();
		let tar = event.target;
		if (tar.classList.contains('remove'))
			this.delete(tar);
	},

	delete: function(tar) {
		let key = tar.previousSibling.textContent.split(':')[0];
		this.item = tar.parentElement;
		this.ajax('DELETE', key);
	},

	get: function() {
		this.ajax('GET');
	},

	modify: function(method, response) {
		if (method == 'DELETE')
			this.item.remove();
		else if (method == 'GET') {
			let parser = new DOMParser();
			for (const [key, val] of Object.entries(response.data)) {
				let li = liStr.replace(/key/, key);
				li = li.replace(/val/, val);
				li = parser.parseFromString(li, 'text/html').querySelector('li');
				this.list.appendChild(li);
			}
		}
	},

	init: function() {
		this.list = document.querySelector('.js-list');
		this.list.addEventListener('click', this.click.bind(this), false);
		this.get();
	}
};
itemList.init();