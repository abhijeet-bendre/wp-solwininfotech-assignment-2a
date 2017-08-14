jQuery(document).ready(function(){
  /** global: wpsa_ajax_nonce */
  'use strict';
  jQuery(':checkbox').change(function() {

    var multi_select = jQuery(this).next() ;
    if(this.checked) {
      var data = {
        'action'    : 'wpsa_get_selected_post_types',
        'checked_post_type' : jQuery(this).data('post-type'),
        'field_id'  : multi_select.data('field-id'),
        'wpsa_ajax_nonce' : wpsa_ajax_nonce
      };
      if( data.post_type !== '' &&  data.field_id !== '' )
      {
        jQuery.post(ajaxurl, data, function(response) {
          multi_select.prop('disabled', false);
          multi_select.html(response);
        });
      }
    }
    else {
      if( multi_select.has('option').length === 0 ) {
        multi_select.prop('disabled', true);
      }
      multi_select.html('');
    }
  });
});
