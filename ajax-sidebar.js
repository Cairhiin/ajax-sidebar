jQuery(document).ready( function() {
  var current_page = 2;
  jQuery('.sidebar__content__ajax').on('click',function(){
    var type = parseInt(jQuery(this).data('tab'), 10);
    var button = jQuery(this);
    var spinner = '<i class="fas fa-spinner fa-spin"></i>';

    jQuery.ajax({
			url: addSidebarContent.ajaxurl,
			data: {
        action: 'get_sidebar_posts',
        page: current_page,
        type: type
     },
      dataType: 'json',
			type: 'post',
			beforeSend : function ( xhr ) {
        button.text('');
				button.append(spinner);
			},
			success: function(res){
				if(res) {
					button.remove('.fa-spinner');
          button.text('Näytä lisää');
          button.before(res.data);
					current_page++;
				} else {
					button.remove();
				}
			}
		});
    }
  );
});
