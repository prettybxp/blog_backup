jQuery(document).ready(function() {
    var chilly_aboutpage = chillyLiteWelcomeScreenCustomizerObject.aboutpage;
    var chilly_nr_actions_required = chillyLiteWelcomeScreenCustomizerObject.nr_actions_required;

    /* Number of required actions */
    if ((typeof chilly_aboutpage !== 'undefined') && (typeof chilly_nr_actions_required !== 'undefined') && (chilly_nr_actions_required != '0')) {
        jQuery('#accordion-section-themes .accordion-section-title').append('<a href="' + chilly_aboutpage + '"><span class="chilly-actions-count">' + chilly_nr_actions_required + '</span></a>');
    }

    /* Upsell in Customizer (Link to Welcome page) */
    if ( !jQuery( ".chilly-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('<li class="accordion-section chilly-upsells">');
    }
    if (typeof chilly_aboutpage !== 'undefined') {
        jQuery('.chilly-upsells').append('<a style="width: 80%; margin: 5px auto 15px auto; display: block; text-align: center;" href="' + chilly_aboutpage + '" class="button" target="_blank">{themeinfo}</a>'.replace('{themeinfo}', chillyLiteWelcomeScreenCustomizerObject.themeinfo));
    }
    if ( !jQuery( ".chilly-upsells" ).length ) {
        jQuery('#customize-theme-controls > ul').prepend('</li>');
    }
});