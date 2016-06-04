jQuery(document).ready(function($) {
	$('#insert_gtw_form_link').magnificPopup({
	  items: {
	      src: '#insert_gtw_form',
	      type: 'inline'
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