<?php
if ( !is_user_logged_in() ) {
   //auth_redirect();
}

get_header(); ?>
<div id="primary" class="content-area">

	<main id="main" class="site-main" role="main">
      <section class="my-order-title-row">
         <div class="wrap">
            <div class="row columns">
               <h1 class="text-center">My Orders</h1>
            </div>
         </div>
      </section>
		<section class="my-order-filter-row">
			<div class="wrap">
				<div class="row">
					<div class="columns small-centered large-6">

                  

                  

					</div>
				</div>
			</div>
		</section>
        
        
      <section class="my-orders-table-row">
         <div class="wrap">
            <div class="row">
                <div class="columns small-centered large-7">
                 <table>
                   <thead>
                     <tr>
                       <th><span>Date</span></th>
                       <th><span>Product</span></th>
                       <th><span>Order ID</span></th>
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <td>07/10/2017</td>
                       <td>50/50 Tee Shirt</td>
                       <td>1234</td>
                     </tr>
                   </tbody>
                 </table>

                </div>
            </div>
         </div>
      </section>
      <section class="my-orders-button-row">
         <div class="wrap">
            <div class="row columns text-center">
               <a href="#" class="btn-secondary load-more">Load More</a>
            </div>
         </div>
      </section>
   </main>

</div>

<?php
get_footer();
