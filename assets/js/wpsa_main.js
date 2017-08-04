jQuery(document).ready(function(){
  "use strict";
  jQuery(":checkbox").change(function() {

    var multi_select = jQuery(this).next() ;

    if(this.checked) {
      var data = {
        'action'    : 'wpsa_get_selected_post_types',
        'post_type' : jQuery(this).data('post-type'),
        'field_id'  : multi_select.data('field-id')
      };
      if( data.post_type !== "" &&  data.field_id !== "" )
      {
        jQuery.post(ajaxurl, data, function(response) {
          multi_select.prop('disabled', false);
          multi_select.html(response);
        });
      }
    }
    else {
      if( multi_select.has('option').length == 0 ) {
        multi_select.prop('disabled', true);
      }
      multi_select.html("");
    }
  });
});
