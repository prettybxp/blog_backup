jQuery(document).ready(function() {

	/* If there are required actions, add an icon with the number of required actions in the About chilly page -> Actions required tab */
    var chilly_nr_actions_required = chillyLiteWelcomeScreenObject.nr_actions_required;

    if ( (typeof chilly_nr_actions_required !== 'undefined') && (chilly_nr_actions_required != '0') ) {
        jQuery('li.chilly-w-red-tab a').append('<span class="chilly-actions-count">' + chilly_nr_actions_required + '</span>');
    }

    /* Dismiss required actions */
    jQuery(".chilly-dismiss-required-action").click(function(){

        var id= jQuery(this).attr('id');
        console.log(id);
        jQuery.ajax({
            type       : "GET",
            data       : { action: 'chilly_dismiss_required_action',dismiss_id : id },
            dataType   : "html",
            url        : chillyLiteWelcomeScreenObject.ajaxurl,
            beforeSend : function(data,settings){
				jQuery('.chilly-tab-pane h1').append('<div id="temp_load" style="text-align:center"><img src="' + chillyLiteWelcomeScreenObject.template_directory + '/inc/chilly-info/img/ajax-loader.gif" /></div>');
            },
            success    : function(data){
				jQuery("#temp_load").remove(); /* Remove loading gif */
                jQuery('#'+ data).parent().remove(); /* Remove required action box */

                var chilly_lite_actions_count = jQuery('.chilly-actions-count').text(); /* Decrease or remove the counter for required actions */
                if( typeof chilly_lite_actions_count !== 'undefined' ) {
                    if( chilly_lite_actions_count == '1' ) {
                        jQuery('.chilly-actions-count').remove();
                        jQuery('.chilly-tab-pane').append('<p>' + chillyLiteWelcomeScreenObject.no_required_actions_text + '</p>');
                    }
                    else {
                        jQuery('.chilly-actions-count').text(parseInt(chilly_lite_actions_count) - 1);
                    }
                }
            },
            error     : function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    });

	/* Tabs in welcome page */
	function chilly_welcome_page_tabs(event) {
		jQuery(event).parent().addClass("active");
        jQuery(event).parent().siblings().removeClass("active");
        var tab = jQuery(event).attr("href");
        jQuery(".chilly-tab-pane").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
	}

	var chilly_actions_anchor = location.hash;

	if( (typeof chilly_actions_anchor !== 'undefined') && (chilly_actions_anchor != '') ) {
		chilly_welcome_page_tabs('a[href="'+ chilly_actions_anchor +'"]');
	}

    jQuery(".chilly-nav-tabs a").click(function(event) {
        event.preventDefault();
		chilly_welcome_page_tabs(this);
    });

		/* Tab Content height matches admin menu height for scrolling purpouses */
	 $tab = jQuery('.chilly-tab-content > div');
	 $admin_menu_height = jQuery('#adminmenu').height();
	 if( (typeof $tab !== 'undefined') && (typeof $admin_menu_height !== 'undefined') )
	 {
		 $newheight = $admin_menu_height - 180;
		 $tab.css('min-height',$newheight);
	 }

});
