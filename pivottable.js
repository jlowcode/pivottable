/**
 * PivotTable Visualization
 */
jQuery(document).ready(function () {
	const root_url = document.getElementById('root_url').value;
	define(['jquery', root_url + 'plugins/fabrik_visualization/pivottable/dist/jquery-ui.min.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/jquery.ui.touch-punch.min.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/pivot.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/papaparse.min.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/plotly.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/plotly-basic-latest.min.js',
		root_url + 'plugins/fabrik_visualization/pivottable/dist/plotly_renderers.min.js'],
		function (jQuery, jQueryUI, jQueryTouch, pivotUI, Papa) {
			window.fbVisPivotTable = new Class({

				Implements: [Options],

				options: {},

				initialize: function (element, options) {
					this.setOptions(options);
					this.init();
				},

				init: function () {
					var dataUrl = window.location.href + '?option=com_fabrik&view=list&listid=' + this.options.list_id + '&format=csv';
					if(this.options.arr_elements_csv.length > 0){
						dataUrl += '&incraw=0' + this.options.arr_elements_csv;
					}
					this.options.contextUrl = 
					Papa.parse(dataUrl, {
						download: true,
						skipEmptyLines: true,
						complete: function (parsed) {
							jQuery("#my-pivottable").pivotUI(parsed.data, {
								renderer: jQuery.pivotUtilities.plotly_renderers["Scatter Chart"],
								renderers: jQuery.extend(
									jQuery.pivotUtilities.renderers,
									jQuery.pivotUtilities.plotly_renderers
								),
								rendererOptions: {
									plotly: {
										width: 600,
										height: 600
									}
								}
							});
						}
					});
				}

			});
		})

	String.prototype.replaceAll = function (find, replace) {
		var str = this;
		return str.replace(new RegExp(find, 'g'), replace);
	};

});
