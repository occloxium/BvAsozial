window.Element.prototype.setAttributes = function(attrs) {
	for (var idx in attrs) {
		if ((idx === 'styles' || idx === 'style') && typeof attrs[idx] === 'object') {
				for (var prop in attrs[idx]){this.style[prop] = attrs[idx][prop];}
		} else if (idx === 'html') {
				this.innerHTML = attrs[idx];
		} else {
				this.setAttribute(idx, attrs[idx]);
		}
	}
}
var hexToRGBA = function (hexColor) {
	'use strict';
	var color = {
		r: parseInt(hexColor.substr(1, 2), 16),
		g: parseInt(hexColor.substr(3, 2), 16),
		b: parseInt(hexColor.substr(5, 2), 16),
		a: 1,
		setAlpha: function (alpha) {
			color.a = alpha;
			return color;
		},
		toString: function () {
			return 'rgba(' + color.r + ',' + color.g + ',' + color.b + ',' + color.a + ')';
		}
	};
	return color;
};

function PieChart(pArgs) {
	'use strict';
	this.total = pArgs.total;
	this.current = pArgs.initial;
	this.color = pArgs.color || '#00b84f';
	this.radius = pArgs.radius;
    this.strokeWidth = pArgs.strokeWidth || 10;
	if(typeof pArgs.container === "string"){
		this.container = document.querySelector(pArgs.container);
	} else if(pArgs.containe === undefined){
		this.container = document.querySelector('#pie');
	} else {
		this.container = pArgs.container;
	}
	this.init = function () {
		
		var group = document.createElementNS("http://www.w3.org/2000/svg", 'g'),
			pie = document.createElementNS("http://www.w3.org/2000/svg", 'circle'),
			pieBackface = document.createElementNS("http://www.w3.org/2000/svg", 'circle'),
			text = document.createElementNS("http://www.w3.org/2000/svg", 'text');

		group.setAttribute('transform', 'rotate(-90 ' + (this.radius + 10) + ' ' + (this.radius + 10) + ')');
		this.container.setAttribute('viewBox', '0 0 ' + ((this.radius + 10) * 2) + ' ' + ((this.radius + 10) * 2));

		pie.setAttributes({
			'class':'pie',
			'r': this.radius,
			'cx': this.radius + 10,
			'cy': this.radius + 10,
			'style': {
				'fill': 'transparent',
				'stroke': hexToRGBA(this.color),
				'strokeWidth': this.strokeWidth,
				'transition': 'stroke-dasharray 1.5s ease'
			}
		});
		
		pieBackface.setAttributes({
			'class':'pieBackface',
			'r': this.radius,
			'cx': this.radius + 10,
			'cy': this.radius + 10,
			'style': {
				'fill': 'transparent',
				'stroke': hexToRGBA(this.color).setAlpha(0.25).toString(),
				'strokeWidth': this.strokeWidth
			}
		});
		
		text.setAttributes({
			'x': (this.radius + 10),
			'y': (this.radius + 10),
			'class': 'text',
			'font-family': 'Roboto',
			'font-size': (this.radius + 10) / 2,
			'fill': '#888',
			'text-anchor': 'middle',
			'dy': (this.radius + 10) / 5,
			'html': (Math.floor(this.getValue() * 100)) + '<tspan font-size="' + ((this.radius + 10) / 5) + '" dy="' + (-((this.radius + 10) / 5)) + '">%</tspan>'
		});
		
		group.appendChild(pie);
		group.appendChild(pieBackface);

		this.container.appendChild(group);

		this.container.appendChild(text);
		var save_current = this.current;
		this.setValue(0);
		pie.style.strokeDasharray = this.getDasharray();
		this.setValue(save_current);
	};
	this.setValue = function (n) {
		if (n <= this.total && n >= 0) {
			// Valid percentage
			this.current = n;
			var pie =	this.container.querySelector('.pie'),
					text = this.container.querySelector('.text');
			pie.style.strokeDasharray = this.getDasharray();
			text.innerHTML = (Math.floor(this.getValue() * 100)) + '<tspan font-size="' + ((this.radius + 10) / 5) + '" dy="' + (-((this.radius + 10) / 5)) + '">%</tspan>';
			return this;
		} else {
			window.console.log("Invalid percentage");
			return this;
		}
	};
	this.setTotal = function (n) {
		if (n > 0) {
			this.total = n;
			this.setValue(this.current);
			return this;
		} else {
			window.console.warn('Totals of 0 will break relative calculations due to divisions by 0');
			return this;
		}
	};
	this.getValue = function () {
		if (this.total !== 0) {
			return this.current / this.total;
		} else {
			window.console.error("Division by 0");
			return this;
		}
	};
	this.getDasharray = function () {
		return (this.getValue() * this.radius * 2 * Math.PI) + ' ' + (this.radius * 2 * Math.PI);
	};
	this.init();
}
