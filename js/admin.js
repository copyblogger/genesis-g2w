jQuery(document).ready(function($) {
	$('#insert_gtw_form_link').magnificPopup({
	  items: {
		src: '#insert_gtw_form',
		type: 'inline'
		},
		callbacks: {
			open: function() {

				var data = {
					'action' : 'gtw_get_webinars'
				}

				jQuery.post(ajaxurl, data, function(response) {
					$("#webinar_key").html(response);
				}, 'html');

			},
			close: function() {
				$('#gtw_insert_form').trigger('reset');
			}
		}
	});

	$('body').on('submit', '#gtw_insert_form', function(e) {
		e.preventDefault();
		var webinar = $("#webinar_key").val();
		//alert(product);
		var shortcode = '[webinar key="'+webinar+'"]';
		parent.parent.tinymce.activeEditor.execCommand('mceInsertRawHTML', false, shortcode);
		$.magnificPopup.close()
	});

});