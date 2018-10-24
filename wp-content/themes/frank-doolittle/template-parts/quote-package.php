<?php
// Let's parse all the package_data if it exists

$data = _s_get_package_data( $package->ID );
/*echo '<pre>';
    var_dump( $data );
echo '</pre>';
*/
  
?>
<div class="package" data-package-id="<?php echo $package->ID;?>">
    
    <h3>Package <span class="package-number"><?php echo $package_number;?></span></h3>
    <form>
    <input type="hidden" name="package-id" value="<?php echo $package->ID;?>">
    <div class="remove-package">Remove<span><b>&times;</b></span></span></div>
    <div class="row">
    
        <div class="small-12 medium-7 large-7 columns">
    
     
                  <div class="columns column-block medium-6 large-6">
                    <div class="select-design">
                       
                       <?php
                        $design = $data['design'];
                        $show_placeholder = $design ? 'hide' : '';
                        $show_selected = $design ? '' : 'hide';
                       ?>
                       <div class="place-holder <?php echo $show_placeholder;?>">
                       <h4>Add a Design</h4>
                        <div class="image">
                        <img src="<?php echo trailingslashit(THEME_IMG);?>place-holder.png" />
                        <div class="button add-to-package" data-open="modal-design"><span><?php echo get_svg('plus');?></span><span class="screen-reader-text">Click Here</span></div></div>
                        <p>Okay to leave blank</p>
                       </div>
                       <div class="selected-design <?php echo $show_selected;?>">
                           <h4>Selected Design</h4>
                           <?php
                           echo _s_get_item( $design );
                           ?>
                      </div>
                    </div>
                 </div>
                
                 
                 <div class="columns column-block medium-6  large-6">
            
                    <div class="select-product">
                        <?php
                        $product = $data['product'];
                        $show_placeholder = $product ? 'hide' : '';
                        $show_selected = $product ? '' : 'hide';
                        ?>
                       <div class="place-holder <?php echo $show_placeholder;?>">
                        <h4>Add a product</h4>
                        <div class="image"><img src="<?php echo trailingslashit(THEME_IMG);?>place-holder.png" />
                        <div class="button add-to-package" data-open="modal-product"><span><?php echo get_svg('plus');?></span><span class="screen-reader-text">Click Here</span></div></div>
                        <p>Okay to leave blank</p>
                       </div>
                       <div class="selected-product <?php echo $show_selected;?>">
                           <h4>Selected product</h4>
                           <?php
                           echo _s_get_item( $product );
                           ?>
                       </div>
                    </div>
            
                 </div>
         
        </div> 
         
         <div class="small-12 medium-5 large-5 columns">
    
            <div class="package-details">
       
               <div class="group total-quantity">
               <h4>Total Quantity</h4>
               <input name="total-quantity" type="text" maxlength="4" class="quantity" value="<?php echo $data['total-quantity'];?>">
               </div>
                              
               <?php
               if( ! empty( $product ) ) {
               
                    $package_attributes = new Package_attributes( $package->ID );
                                    
                    $checked =  $data['sizes'];
                    $show = 'no' == $checked ? false : true;
                                                           
                    $out = '';
                    $out .= $package_attributes->get_confirm_sizes( $checked );
                    $out .= $package_attributes->get_product_sizes( $product, $data['attributes'], $show );
                    
                    printf( '<div class="package-attributes">%s</div>', $out );
               }
               ?> 
                    
            </div><!-- package-details -->
            
            <div class="notes">
            
                <p><a class="add-note" data-toggle="note-<?php echo $package->ID;?>">+ Add Notes:</a></p>
                   
                <div id="note-<?php echo $package->ID;?>" class="note hide" data-toggler=".hide"><textarea name="notes"><?php echo $data['notes'];?></textarea></div>    
            
            </div>
    
         </div>
      </div><!-- row -->
                
  </form>
  
</div>