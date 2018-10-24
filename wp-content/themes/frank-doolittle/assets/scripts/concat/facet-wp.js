(function($) {
    $(document).on('facetwp-loaded', function() {
        $.each(FWP.settings.num_choices, function(key, val) {
            var $parent = $('.facetwp-facet-' + key).closest('.accordion-item');
            (0 === val) ? $parent.hide() : $parent.show();
        });
    });
    
     $(document).on('facetwp-loaded', function() {
        $.each(FWP.facets, function(facet, value) {
            if (FWP.facets[facet].length > 0) {
                //$('.facetwp-facet-'+facet+' .facetwp-toggle:first').click();
            }
        });
     });
})(jQuery);