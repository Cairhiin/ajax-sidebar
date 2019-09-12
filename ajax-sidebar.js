jQuery(document).ready( function() {
  // starting WordPress query on page 2 - the first 5 articles are shown by default
  var currentPage = 2;
  jQuery('.sidebar__content__ajax').on('click',function(){
    var type = parseInt(jQuery(this).data('tab'), 10);
    var button = jQuery(this);
    var spinner = '<i class="fas fa-spinner fa-spin"></i>';

    jQuery.ajax({
			url: addSidebarContent.ajaxurl,
			data: {
        action: 'get_sidebar_posts',
        page: currentPage,
        type: type
     },
      dataType: 'json',
			type: 'post',
			beforeSend : function(xhr) {
        button.text('');
				button.append(spinner);
			},
      error: function(err) {
        console.info("Ajax Error: ", err);
        button.remove();
      },
			success: function(res){
				if(res) {
					button.remove('.fa-spinner');
          button.text('Näytä lisää');
          button.before(res.data);
					currentPage++;
				} else {
					button.remove();
				}
			}
		});
    }
  );
});
