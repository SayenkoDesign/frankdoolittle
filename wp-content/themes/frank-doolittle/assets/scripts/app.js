/*
TODO:
- update modals, reset as needed, remove parent elements if necessary
- validate everything
*/


(function ($) {
    'use strict';
    
    var errors;
    //var messages = JSON.parse(doolittle_rest_object.messages);
    var base_url = doolittle_rest_object.api_url + doolittle_rest_object.api_prefix;
    var config = {
        logging: true
    };
        
    // Stop user from leaving as needed
    var finished = true;
    
    var preventNavigation = function () {
         if( ! finished ) {
             //log('Not finished');
             return false;
         }
     };
    
    $(window).bind('beforeunload', preventNavigation );
    $(window).bind('unload', preventNavigation );
    

    // Toggle checkbox/selected items
    $('.reveal').on('change', '.column .select-btn input[type="checkbox"]', function () {
        $(this).parent('.select-btn').toggleClass('checked');
        $(this).closest('.column').toggleClass('checked');
        $('.add-favorites-to-quote').toggleClass('disabled', $(this).filter(':checked').size() < 1);
    });
    
    
    // Modal - Design - should we count the grid?
    $(document).on(
      'closeme.zf.reveal', '#modal-favorites', function () {
        // Special case, hide the actions if no designs/products
        if( $(this).find('.grid').children('.column').length === 0 ) {
            $(this).find('.actions').addClass('hide');
        }
        else {
            $(this).find('.actions').removeClass('hide');
        }
      }
    );
    
    
    
    // Remove error messaage when closing modal
    $(document).on(
      'closed.zf.reveal', '[data-reveal]', function () {
        $(this).find('.msg .error').remove();
      }
    );
    
    // Color selector
    $('#packages').on('change', '.package .attributes .select-color', function () {
        
        var $this= $(this);
        var sizes = $(this).find(':selected').data('sizes');
        var $select = $this.parents('.attributes ul').find('.select-size');
         
        $select.find('option').remove(); 
        
        // We don't want it to be blank
        if( ! sizes ) {
            sizes = {'':'Size'};
        }
         
        $.each(sizes,function(key, value) 
        {
            $select.append('<option value=' + key + '>' + value + '</option>');
        });
        
        /*
        if( $('option', $select).size() == 1 ) {
            $select.prop('disabled', 'disabled');
        }
        else {
            $select.prop('disabled', '');
        }
        */
        
    });
    
     
    // Disable links
    $(document).on('click', '.disabled', function(e){
        e.preventDefault();
        return false;
    });
    
    
    // Cleanup reveals
    $(document).on('open.fndtn.reveal', '[data-reveal]', function () {
      var modal = $(this);
      modal.removeAttribute( 'data-package-id' );
    });
    
    
    // Helper function for selecting outerHtml
    jQuery.fn.outerHTML = function(s) {
        return (s)
          ? this.before(s).remove()
          : jQuery("<div>").append(this.eq(0).clone()).html();
    } 
    
     
    // Monitor all package changes, add delay because of lazy loading attribute options
    $('#packages').on('change keyup', '.package form :input', function () {
        var $package = $(this).parents('.package');
        setTimeout(function(){
          updatePackage($package);
        }, 500);
        
    });

    
    // FAVORITES

    // Add a single product/design to favorites
    $('.add-to-favorites').on('click', addToFavorites);

    // Remove single product/design from favorites
    $('#modal-favorites').on('click', '.close-btn', removeFavorite);
    
    // Move products/designs from favorites to quotes
    $('#modal-favorites').on('click', '.add-favorites-to-quote', addFavoritesToQuote);
 
    // Add single product/design to quote
    $('.add-to-quote').on('click', addToQuote);

    
    // QUOTES
    
    // remove designs from quote
    $('#modal-product, #modal-design, #modal-quote').on('click', '.close-btn', removeItemsFromQuote );
    
    // remove all products/designs from quote
    $('#modal-product, #modal-design, #modal-quote').on('click', '.clear-all', function(e){
        e.preventDefault();
        removeAllItemsFromQuote( '#modal-product' );
        removeAllItemsFromQuote( '#modal-design' );
        removeAllItemsFromQuote( '#modal-quote' );
    });
    


    
    
    
    // PACKAGES
    
    $(window).load(function() {
        // We always need a package
        resetPackages();
    });
    
    // Add package
    $('.add-package ').on('click', addPackage);
    
    // remove a single package
    $('#packages').on('click', '.package .remove-package', removePackage);
    
    // remove all package
    $('.remove-packages').on('click', removeAllPackages);
    
    
    // Package add design
    $('#packages').on('click', '.package .select-design .add-to-package', addToPackage);
    
    // Package remove design
    $('#packages').on('click', '.package .selected-design .close-btn', removeDesignFromPackage);
    
    // Package add product
    $('#packages').on('click', '.package .select-product .add-to-package', addToPackage);
    
    // Package remove product
    $('#packages').on('click', '.package .selected-product .close-btn', removeProductFromPackage);
    
    // Add design to package
    $('#modal-design').on('click', '.grid .column .image', addDesignToPackage);
    
    // Add product to package
    $('#modal-product').on('click', '.grid .column .image', addProductToPackage);
    
    

    // respond to a change of size selection
    $('.confirm-sizes input[name="sizes"]:checked').on('change', respondToSizeSelection);
      
    // add a copy of the attirbutes sections (includes +-)
    $('#packages').on('click', '.package .product-attributes .add-attr', addAttributeElement);

    // remove the attributes section
    $('#packages').on('click', '.package .product-attributes .remove-attr', removeAttributeElement);
    
         
     
    function addToFavorites(e) {
        
        //log('addToFavorites');
        
        e.preventDefault();
        
        var $this   = $(this);
        var post_id = $this.data('post-id');

        callResource({
            method: 'POST',
            endpoint: 'favorite/add'
        }, {
            post_ids: [post_id]
        }, function (response) {
            updateCounts(response);
            addToFavoriteModal(response);
            $('.add-to-favorites').addClass('disabled');
        });
    }
    
    
    function addToFavoriteModal(item) {
        
        //log('addToFavoriteModal');

        var favorites = item.post_ids;
        var design    = favorites.design;
        var product   = favorites.product;

        if (design) {
            addItemToFavoriteModal(design, 'design');
        }

        if (product) {
            addItemToFavoriteModal(product, 'product');
        }
    }


    function addItemToFavoriteModal(data, type ) {
        
        //log('addItemToFavoriteModal');

        var template = wp.template('modal-item-favorite'); // uses script tag ID minus "tmpl-"

        var html = template(data);

        var $modal = $('#modal-favorites');
        
        $modal.find('.grid-container.' + type + ' .grid').append(html);
        
        if( $modal.find('.grid').children('.column').length > 0 ) {
            $modal.find('.actions').removeClass('hide');
        }
    }

    
    function removeFavorite(e) {
        
        //log('removeFavorite');
        
        e.preventDefault();

        var post_id = $(this).parents('.column').data('post-id');
        
        var $modal = $('#modal-favorites');

        callResource({
            method: 'POST',
            endpoint: 'favorite/delete'
        }, {
            post_ids: [post_id]
        }, function (response) {
            removeFromModal($modal,response);            
            $('.add-to-favorites').removeClass('disabled');
            
            // Special case, hide the actions if no designs/products
            if( $modal.find('.grid').children('.column').length === 0 ) {
                 $modal.find('.actions').addClass('hide');
            }
            
        });
    }
    
    
    
    function addToModal($modal, item) {
          
        var template = wp.template('modal-item'); // uses script tag ID minus "tmpl-"
        var html = template(item);
        $modal.find('.grid').append(html);
        
        if( $modal.find('.grid').children('.column').length ) {
            $modal.addClass('has-items');
        }
    }
    
    
    function addToQuoteModal($modal, item) {
          
        var template = wp.template('modal-item'); // uses script tag ID minus "tmpl-"
        var html = template(item);
        var $which_modal = $modal.find( '.' + item.type + ' .grid');
        log( $which_modal );
        $which_modal.append(html);
        
        if( $modal.find('.grid').children('.column').length ) {
            $modal.addClass('has-items');
        }
    }
    
    
    function removeFromModal($modal, response) {
        _.each(response.post_ids, function (id) {
            log('.column[data-post-id="' + id + '"]');
            $modal.find('.column[data-post-id="' + id + '"]').remove();
         });
         
         // Update counts
         updateCounts(response);
    }
    
     
    function addFavoritesToQuote(e) {
        
        //log('addFavoritesToQuote');
        e.preventDefault();   

        var post_ids = [];
        
        var $modal = $('#modal-favorites');

        $('.column input[type="checkbox"]:checked', $modal ).each(function () {
            post_ids.push( $(this).val() );
        });
          
        // TODO: Show eror message top of modal
        if( ! post_ids.length ) {
            return;
        }
                  
        callResource({
            method: 'POST',
            endpoint: 'quote/add'
        }, {
            post_ids: post_ids
        }, function (response) {
            addQuotesToModal(response);
            $('.add-to-quote').addClass('disabled');
        });
    }
    
      
    
    function removeFromFavoritesModal(response) {
        
        var $favorites_modal = $('#modal-favorites');
        
        var post_ids = _.pluck(response.post_ids, 'post_id');
        
        _.each(post_ids, function (id) {
            $favorites_modal.find('.column[data-post-id="' + id + '"]').remove();
         });
         
         // Update counts
         updateCounts(response);
        
    }
    
    
    
    function addQuotesToModal(response) {
                
        _.each(response.post_ids, function (item) {
            var $modal = $('#modal-' + item.type );
            addToModal($modal, item );
            addToQuoteModal( $('#modal-quote'), item );
         });
         
         // Update counts
         updateCounts(response);
         
         // unset checkboxes
         $('#modal-favorites').find('input:checkbox').removeAttr('checked');
         $('#modal-favorites').find('.select-btn').removeClass('checked');
         $('#modal-favorites').find('.column').removeClass('checked');
         $('#modal-favorites').find('.add-favorites-to-quote').addClass('disabled');
         
    }
    
    // QUOTES
    
    
    function addToQuote(e) {
        //log('addToQuote');
        e.preventDefault();

        var post_id = $(this).data('post-id');

        callResource({
            method: 'POST',
            endpoint: 'quote/add'
        }, {
            post_ids: [post_id]
        }, function (response) {
            updateCounts(response);
            $('.add-to-quote').addClass('disabled');
        });
    }
    
    
    
    function removeAllItemsFromQuote( modal ) {

        var post_ids = [];
        
        var $modal = $(modal);

        $('.column input[type="hidden"]', $modal ).each(function () {
            post_ids.push( $(this).val() );
        });
        
        // TODO: Show eror message top of modal
        if( ! post_ids.length ) {
            return;
        }
                
        // Only remove allowed
        var remove_ids = _allowedToRemove(post_ids);
        
        if( ! remove_ids.length ) {
            trigger_error( 'Products and Designs added to packages cannot be removed from Quote.', $modal );
            return;
        } 
        
        
        callResource({
            method: 'POST',
            endpoint: 'quote/delete'
        }, {
            post_ids: remove_ids
        }, function (response) {
             removeFromModal($modal, response);
        });
    }
        
    
    
    function removeItemsFromQuote(e) {
        
        //log('removeItemsFromQuote');
        e.preventDefault();

        var post_id = $(this).prev('input[type="hidden"]').val();
        
        var $modal = $(this).parents( '.reveal' );
        
        // Don't remove if its being used.
        var exists = _itemExists(post_id);
                
        if(exists) {
           trigger_error( 'Products and Designs added to packages cannot be removed from Quote.', $modal );
           return; 
        }

        callResource({
            method: 'POST',
            endpoint: 'quote/delete'
        }, {
            post_ids: [post_id]
        }, function (response) {
            removeFromModal( $('#modal-product'), response);
            removeFromModal($('#modal-design'), response);
            removeFromModal($('#modal-quote'), response);
        });
    }
        
    
    // Check whether this can be removed or not
    function _itemExists(post_ids) {
        
        post_ids = _.isArray(post_ids) ? post_ids : [post_ids]; 
        
        var ids = [];
        
        var $packages = $('#packages');
        $('.package input[name="post_ids[]"]', $packages ).each(function () {
            ids.push( $(this).val() );
        });
        
        var found = _.intersection(ids, post_ids );
        
        return _.size(found);
    }
    
    
    
    // Create array of allowed ids to remove
    function _allowedToRemove(post_ids) {
        
        post_ids = _.isArray(post_ids) ? post_ids : [post_ids]; 
        
        var ids = [];
        
        var $packages = $('#packages');
        $('.package input[name="post_ids[]"]', $packages ).each(function () {
            ids.push( $(this).val() );
        });
        
        return _.difference(post_ids, ids );

    }
    
   // Add to Modal
   
    
     
    // update favorite count
    function updateFavoritesCount(newValue) {
        //log('updateFavoritesCount');

        // modify display to hide circle indicator for 0 items
        newValue = (newValue > 0) ? newValue : '';

        $('.favorites-and-quotes .favorites .number').text(newValue);
    }

    // update quote count
    function updateQuoteCount(newValue) {
        //log('updateQuoteCount');

        // modify display to hide circle indicator for 0 items
        newValue = (newValue > 0) ? newValue : '';
        
        var quote_text = (newValue > 0) ? 'My Quote' : 'Build Quote';
        $('.favorites-and-quotes > a.quotes > p').text(quote_text);
        $('.favorites-and-quotes > a.quotes > div > span').text(newValue);
    }

    function updateCounts(response) {
        //log('updateCounts');
        updateQuoteCount(response.quotes_count);
        updateFavoritesCount(response.favorites_count);
    }
    
    
    
    // PACKAGES
    
    function addPackage() {
        //log('addPackage');
        
        callResource({
            method: 'GET',
            endpoint: 'package/add'
        }, {
            post_ids: []
        }, function (response) {
            
            var package_id = response.package_id;
             
            if( ! package_id ) {
                // TODO: Show Error
                return;
            }
            
            var $packages = $('#packages');
            var package_number = $packages.children().size() + 1;
            
            var data = {
                package_id: package_id,
                package_number: package_number
            };
                        
            var template = wp.template('single-package');
            var html = template(data);
            
            $packages.append(html);
            
            // TODO: Remove          
            $('.package .product-attributes .attributes ul:first-child', '#packages').find('.remove-attr').hide();
        });
         
    }
    
    
    function removePackage(e) {
        
        //log('removePackage');
        
        e.preventDefault();

        var post_id = $(this).parents('.package').data('package-id');
        
        callResource({
            method: 'POST',
            endpoint: 'package/delete'
        }, {
            post_ids: [post_id]
        }, function (response) {
            
            if( _.isArray( response.packages ) ) {
                _.each(response.packages, function (id) {
                    // Remove each package
                    var $packages = $('#packages');
                    $packages.find('.package[data-package-id="' + id + '"]').remove();
                });
                updateCounts(response);
                resetPackages();
            }
            else {
                setErrorMessage( 'Could not delete package' );   
            }
            
        });
        
    }
    
    
    function removeAllPackages(e) {
        
        //log('removeAllPackages');
        
        e.preventDefault();

        var post_ids = [];
        
        var $packages = $('#packages');

        $('.package', $packages ).each(function () {
            post_ids.push( $(this).data('package-id') );
        });
                
        // TODO: Show eror message top of modal
        if( ! post_ids.length ) {
            return;
        }

        callResource({
            method: 'POST',
            endpoint: 'package/delete'
        }, {
            post_ids: post_ids
        }, function (response) {
            _.each(response.packages, function (id) {
                // Remove each package
                $packages.find('.package[data-package-id="' + id + '"]').remove();
            });
            updateCounts(response);
            resetPackages();
        });
    }
    
    // Making sure we always have a single package
    function resetPackages() {
        
        log('resetPackages');
        
        var $packages = $('#packages');
        
        // only reset if were on the right page
        if( ! $packages.length ) {
            return false;
        }
        
        //TODO: Remove
        // hide remove button from first set of package attributes
        $('.package', $packages ).each(function () {
            if( $(this).find('.product-attributes .attributes ul').size() === 1 ) {
                $(this).find('.product-attributes .attributes ul').find('.remove-attr').hide();
            }
        });
                
        // Reset Package Numbers
        resetPackageNumbers();
        
        var count_packages = $packages.children().size();
                
        if( count_packages ) {
            return;
        }
        
                
        callResource({
            method: 'GET',
            endpoint: 'package/add'
        }, {
            post_ids: []
        }, function (response) {
            
            var package_id = response.package_id;
             
            if( ! package_id ) {
                // TODO: Show Error
                return;
            }
            
            var $packages = $('#packages');
            var package_number = $packages.children().size() + 1;
            
            var data = {
                package_id: package_id,
                package_number: package_number
            };
                        
            var template = wp.template('single-package');
            var html = template(data);
            
            $packages.append(html);
            
            // Reset Package Numbers
            resetPackageNumbers();
        });
         
    }
    
    
    function resetPackageNumbers() {
        //log('resetPackageNumbers');
        
        $('#packages').children('.package').each(function (index) {
            $(this).find('.package-number').html(index + 1);
        });    
    }
    
    
    function addToPackage(e) {
        //log('addToPackage');
        
        e.preventDefault();
        
        var $this = $(this);
                
        var target_model = $('#' + $this.data('open'));
        var package_id =  $this.parents('.package').data('package-id');
        target_model.attr('data-package-id', package_id );
        
    }
    

    function addDesignToPackage() {
        //log('addDesignToPackage');
        
        var $this = $(this);
        var $column = $this.parents('.column');
        
        // clicking on the thumbnail
        var $modal = $('#modal-design');
        var package_id = $modal.attr('data-package-id');
        
        var $packages = $('#packages');
        var $package = $packages.find('.package[data-package-id="' + package_id + '"]');
                
        var $place_holder = $package.find('.select-design .place-holder');
        var $selected = $package.find('.selected-design');
        
        $place_holder.addClass('hide');
        $selected.append($column.outerHTML());
        $selected.removeClass('hide');
        
        $modal.foundation('close');
        
        // Need to fire package/update
        updatePackage($package);
        
    }
    
    
    function removeDesignFromPackage() {
        //log('removeDesignFromPackage');
        
        var $this = $(this);
        var $column = $this.parents('.column');
        var $package = $this.parents('.package');
                  
        var $place_holder = $column.parents('.select-design').find('.place-holder');
        var $selected = $column.parents('.selected-design');
        
        $column.remove();
          
        $place_holder.removeClass('hide');
        $selected.addClass('hide');
        
        // Need to fire package/update
        updatePackage($package);
        
    }
    
    
    function addProductToPackage() {
        //log('addProductToPackage');
        
        var $this = $(this);
        var $column = $this.parents('.column');
        var post_id = $column.data('post-id');
        
        // clicking on the thumbnail
        var $modal = $('#modal-product');
        var package_id = $modal.attr('data-package-id');
        
        var $packages = $('#packages');
        var $package = $packages.find('.package[data-package-id="' + package_id + '"]');
        
        var $place_holder = $package.find('.select-product .place-holder');
        var $selected = $package.find('.selected-product');
        
        $place_holder.addClass('hide');
        $selected.append($column.outerHTML());
        $selected.removeClass('hide');
        
        $modal.foundation('close');
        
        // We need to grab the product attributes
        loadProductAttributes( $('.package-details', $package), post_id);
        
        // Need to fire package/update
        updatePackage($package);
        
    }
    
    
    function removeProductFromPackage() {
        //log('removeProductFromPackage');
        
        var $this = $(this);
        var $column = $this.parents('.column');
        var $package = $this.parents('.package');
          
        var $place_holder = $column.parents('.select-product').find('.place-holder');
        var $selected = $column.parents('.selected-product');
        
        $column.remove();
          
        $place_holder.removeClass('hide');
        $selected.addClass('hide');
        
        removeProductAttributes($package);
        
        // Need to fire package/update
        updatePackage($package);
        
    }
    
    
    // Product Attributes
    $('#packages').on('change', 'input[name="sizes"]', function() {
      // this, in the anonymous function, refers to the changed-<input>:
      // select the element(s) you want to show/hide:
      $(this).parents('.package-details').find('.product-attributes')
          .toggleClass('hide');
    // trigger the change event, to show/hide the .business-fields element(s) on
    // page-load:
    }).change();
    
    function loadProductAttributes( element, post_id ) {
        
        if( ! post_id ) {
            //TODO: Error - could not find product 
            return;
        }
        
        finished = false;
    
        $.ajax({
           method: 'GET',
            // Here we supply the endpoint url, as opposed to the action in the data object with the admin-ajax method
            url: doolittle_rest_object.api_url + 'product/' + post_id,
            beforeSend: function (xhr) {
                // Here we set a header 'X-WP-Nonce' with the nonce as opposed to the nonce in the data object with admin-ajax
                xhr.setRequestHeader('X-WP-Nonce', doolittle_rest_object.api_nonce);
            
            }
        }).done( function (response) {
            
            if(response.package_attributes) {
                element.append(response.package_attributes);
            }
                
        }).fail( function () {
            
            
        }).always( function () {
            
            finished = true;
            //log('finished');
            
        });
        
    }
    
    
    function removeProductAttributes(element) {
        element.find('.package-attributes').remove();
    }
    
    
    
    function updatePackage(element) {
        
        //log('updatePackage');
                
        var form = element.find('form');
        
        var data = form.serializeJSON();
         
        callResource({
            method: 'POST',
            endpoint: 'package/update'
        }, {
            data: data
        });
 
    }


    // End working code

    function addAttributeElement(e) {
        //log('addAttributeElement');
        e.preventDefault();
        
        var $parent = $(this).parents('.package').find('.attributes');
                  
        addRow($parent);  
        
        if($('ul', $parent ).size() > 1) {
            //alert("Can't remove row.");
            $(".remove-attr").show();
        }
        
        var $package = $(this, '#packages').parents('.package');
        
        // TODO: we might not need this if we show empty inputs by default
        updatePackage($package);

    }

    function removeAttributeElement(e) {
        //log('removeAttributeElement');
        e.preventDefault();
        
        var $package = $(this).parents('.package');
        var $row = $package.find('.attributes');
        
        if($('ul', $row ).size() == 1) {
            //alert("Can't remove row.");
            $(".remove-attr", $row).hide();
        } else {
            removeRow($(this));
        }
        
        if($('ul', $row ).size() == 1) {
            $(".remove-attr", $row).hide();
        }
        
        
        updatePackage($package);
        
     }

    function addRow(element) {
        $('ul:last-child', element).clone(true,true)
        .find(':input').val('').end().find('.select-size option:not(:first)').remove().end().appendTo(element);
     }
    
    function removeRow(button) {
        button.closest('.attributes ul').remove();
    }
    
     
 
    function respondToSizeSelection() {
        //log('respondToSizeSelection');
        var selection = $('input[name="sizes"]:checked').val();

        if (selection === 'yes') {
            $(' .product-attributes').removeClass('hidden');
        }
    }
    
    
     
    function trigger_error( msg, display ) {          
          var message = '<div class="error">' + msg + '</div>';
          show_msg( message, display );
     }
    
    
    function show_msg( message, display ) {
        
        var $where = $('.msg');
        
        if( typeof display !== 'undefined' ) {
           $where = $('.msg', display); 
        }
        
        $where.append(message);
        setTimeout(function() {
          $('.msg').children('div').remove();
        }, 3000);

    }
    
    

    function log(msg) {
        if (config.logging) {
            console.log(msg);
        }
    }



    function callResource(config, data, doneCallback, failCallback) {

        // Set finished so we can block visitor from leaving before we finish
        finished = false;
        
        // Fire our ajax request!
        return $.ajax({
           method: config.method,
            // Here we supply the endpoint url, as opposed to the action in the data object with the admin-ajax method
            url: base_url + config.endpoint,
            data: data,
            beforeSend: function (xhr) {
                // Here we set a header 'X-WP-Nonce' with the nonce as opposed to the nonce in the data object with admin-ajax
                xhr.setRequestHeader('X-WP-Nonce', doolittle_rest_object.api_nonce);
            
            }
        }).done( function (response) {
            
            if (typeof doneCallback === 'function') {
                doneCallback(response);
            }
                
        }).fail( function () {
            
            if (typeof failCallback === 'function') {
                failCallback();
            }
        }).always( function () {
            
            finished = true;
            //log('finished');
            
        });
    }
})(jQuery);
