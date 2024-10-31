<?php
/*
 * Elementor Product Isotope Filter for Elementor
 * Author & Copyright: wpOcean
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WCoIsotopFilter_Product_Filter extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'wco_product_filter';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Product Isotop Filter', 'wco-isotop-filter' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['WCoIsotopFilter'];
	}

	/**
	 * Retrieve the list of scripts the WCoIsotopFilter Title widget depended on.
	 * Used to set scripts dependencies required to run the widget.
	*/
	
	public function get_script_depends() {
		return ['wco_product_script'];
	}
	
	
	/**
	 * Register WCoIsotopFilter Title widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

	$posts = get_posts( 'post_type="product"&numberposts=-1' );
    $PostID = array();
    if ( $posts ) {
      foreach ( $posts as $post ) {
        $PostID[ $post->ID ] = $post->ID;
      }
    } else {
      $PostID[ __( 'No ID\'s found', 'wco-isotop-filter' ) ] = 0;
    }

		/**
		* Get Post terms
		*/
		 function get_terms_names( $term_name = '', $output = '', $hide_empty = false ){
			$return_val = [];
			$terms = get_terms([
			    'taxonomy'   => $term_name,
			    'hide_empty' => $hide_empty,
			]);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach( $terms as $term ){
					if( 'id' == $output ){
						$return_val[$term->term_id] = $term->name;
					}
					else{
						$return_val[$term->slug] = $term->name;
					}
				}
			}
			return $return_val;
		}

		$this->start_controls_section(
			'product_section_options',
			[
				'label' => esc_html__( 'Product Filter Options', 'wco-isotop-filter' ),
			]
		);
		$this->end_controls_section();// end: Section


		$this->start_controls_section(
			'section_product_listing',
			[
				'label' => esc_html__( 'Product Listing', 'wco-isotop-filter' ),
			]
		);

		$this->add_control(
			'pr_list_style',
			[
				'label' => __( 'Product Type', 'wco-isotop-filter' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'pr-recent' => esc_html__( 'Recent', 'wco-isotop-filter' ),
					'pr-onsales' => esc_html__( 'On Sale', 'wco-isotop-filter' ),
					'pr-toprated' => esc_html__( 'Toprated', 'wco-isotop-filter' ),
					'pr-random' => esc_html__( 'Random', 'wco-isotop-filter' ),
					'pr-featured' => esc_html__( 'Featured', 'wco-isotop-filter' ),
					'pr-bestsell' => esc_html__( 'Bestsell', 'wco-isotop-filter' ),
				],
				'default' => 'pr-recen',
			]
		);

		$this->add_control(
			'product_limit',
			[
				'label' => esc_html__( 'Product Limit', 'wco-isotop-filter' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'wco-isotop-filter' ),
			]
		);
		$this->add_control(
			'product_order',
			[
				'label' => __( 'Order', 'wco-isotop-filter' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC' => esc_html__( 'Asending', 'wco-isotop-filter' ),
					'DESC' => esc_html__( 'Desending', 'wco-isotop-filter' ),
				],
				'default' => 'DESC',
			]
		);
		$this->add_control(
			'product_orderby',
			[
				'label' => __( 'Order By', 'wco-isotop-filter' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wco-isotop-filter' ),
					'ID' => esc_html__( 'ID', 'wco-isotop-filter' ),
					'author' => esc_html__( 'Author', 'wco-isotop-filter' ),
					'title' => esc_html__( 'Title', 'wco-isotop-filter' ),
					'date' => esc_html__( 'Date', 'wco-isotop-filter' ),
				],
				'default' => 'date',
			]
		);
		$this->add_control(
			'all_category_title',
			[
				'label' => esc_html__( 'All Title', 'wco-isotop-filter' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'category_title',
			[
				'label' => esc_html__( 'Custom Title', 'wco-isotop-filter' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'product_show_category',
			[
				'label' => __( 'Select Category', 'wco-isotop-filter' ),
				'type' => Controls_Manager::SELECT,
				'default' => [],
				'options' => get_terms_names( 'product_cat'),
				'multiple' => true,
			]
		);

		$repeater->add_control(
			'product_show_id',
			[
				'label' => __( 'Certain ID\'s?', 'wco-isotop-filter' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => $PostID,
				'multiple' => true,
			]
		);
		$this->add_control(
			'product_categories',
			[
				'label' => esc_html__( 'Filter Categories', 'wco-isotop-filter' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'category_title' => esc_html__( 'Category', 'wco-isotop-filter' ),
					],
					
				],
				'fields' =>  $repeater->get_controls(),
				'title_field' => '{{{ category_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Background
		$this->start_controls_section(
			'section_bg_style',
			[
				'label' => esc_html__( 'Background', 'wco-isotop-filter' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'shop_bg_color',
			[
				'label' => esc_html__( 'Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->add_responsive_control(
			'shop_bg_padding',
			[
				'label' => esc_html__( 'Title Padding', 'wco-isotop-filter' ),
				'type' => Controls_Manager::DIMENSIONS,				
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wco-product-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section


		// Tab Color
		$this->start_controls_section(
			'section_filter_style',
			[
				'label' => esc_html__( 'Filter', 'wco-isotop-filter' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'shop_filter_color',
			[
				'label' => esc_html__( 'Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-filters .product-filter-btn .product-btn' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'shop_filter_devider_color',
			[
				'label' => esc_html__( 'Devider Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-filters .product-filter-btn .product-btn:before' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'shop_active_filter_color',
			[
				'label' => esc_html__( 'Active Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-filters .product-filter-btn .product-btn.current' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'shop_filter_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-filters .product-filter-btn' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'wco-isotop-filter' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'selector' => '{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text h2',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text h2 a' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Title Padding', 'wco-isotop-filter' ),
				'type' => Controls_Manager::DIMENSIONS,				
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Price
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'wco-isotop-filter' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'selector' => '{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text .product-price ul li',
			]
		);
		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text .product-price ul li del' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'price_active_color',
			[
				'label' => esc_html__( 'Active Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text .product-price ul li' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'price_padding',
			[
				'label' => __( 'Title Padding', 'wco-isotop-filter' ),
				'type' => Controls_Manager::DIMENSIONS,				
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text .product-price ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Overly
		$this->start_controls_section(
			'section_overly_style',
			[
				'label' => esc_html__( 'Overly', 'wco-isotop-filter' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'overly_color',
			[
				'label' => esc_html__( 'Overly Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single:before' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide h2 a, .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .product-price ul li' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_price_color',
			[
				'label' => esc_html__( 'Price Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .product-price ul li:last-child' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_btn_color',
			[
				'label' => esc_html__( 'Button Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .cart-btn' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_btn_bg_color',
			[
				'label' => esc_html__( 'Button BG Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .cart-btn' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_btn_hover_color',
			[
				'label' => esc_html__( 'Button hover Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .cart-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);	
		$this->add_control(
			'overly_btn_hover_bg_color',
			[
				'label' => esc_html__( 'Button BG hover Color', 'wco-isotop-filter' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wco-product-section .product-wrap .gallery-container .wco-product-single .wco-product-text-hide .cart-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);	
		$this->end_controls_section();// end: Section

		
	}

	/**
	 * Render Title widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		$pr_list_style = $settings['pr_list_style'] ? $settings['pr_list_style'] : '';
		$all_category_title = $settings['all_category_title'] ? $settings['all_category_title'] : '';
		$product_limit = $settings['product_limit'] ? $settings['product_limit'] : 8;
		$product_order = $settings['product_order'] ? $settings['product_order'] : '';
		$product_orderby = $settings['product_orderby'] ? $settings['product_orderby'] : '';
		$product_categories = $settings['product_categories'] ? $settings['product_categories'] : [];

		// Turn output buffer on
		$meta_query = \WC()->query->get_meta_query();
		$tax_query  = \WC()->query->get_tax_query();

	$product_show_category = '';
	foreach ($product_categories as $key => $category) {
		$product_show_category .= $category['product_show_category'].',';
	}

	$args = array(
	    'post_type'           	=> 'product',
	    'post_status'			=> 'publish',
	    'product_cat'			=> rtrim($product_show_category, ',') ,
			'ignore_sticky_posts'	=> 1,
	    'posts_per_page'      	=> (int)$product_limit,
	);

	if ( $pr_list_style === 'pr-bestsell' ) {

	  	$args['meta_key'] = 'total_sales';
	  	$args['orderby']  = 'meta_value_num';

    } else if ( $pr_list_style === 'pr-featured' ) {

    	$tax_query[] = array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'featured',
        'operator' => 'IN',
      );

      $args['order']   = $product_order;
    	$args['orderby'] = $product_orderby;

    } else if ( $pr_list_style === 'pr-random' ) {

    	$args['order']   = $product_order;
    	$args['orderby'] = 'rand';

    } else if ( $pr_list_style === 'pr-recent' ) {

    	$args['order']   = $product_order;
    	$args['orderby'] = 'date';

    } else if ( $pr_list_style === 'pr-onsales' ) {

    	$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
    	$args['order']   = $product_order;
    	$args['orderby'] = $product_orderby;

    } else if ( $pr_list_style === 'pr-toprated' ) {

    	$args['meta_key'] = '_wc_average_rating';
    	$args['order']   = $product_order;
    	$args['orderby'] = $product_orderby;

    } else {
    	$args['order']   = $product_order;
    	$args['orderby'] = $product_orderby;
    }

    $args['meta_query'] = $meta_query;
    $args['tax_query']  = $tax_query;

	//print_r($args);
	$wco_products_list = new \WP_Query($args);


	ob_start(); 
	if ($wco_products_list->have_posts()) {
	?>
	<!-- Flash-Sale-area-start -->
  <div class="wco-product-section section-padding">
    <div class="container">
        <div class="product-wrap">
            <div class="row">
                <div class="col col-xs-12 sortable-gallery">

              	<div class="gallery-filters">
                  <ul class="product-filter-btn">
                  <?php if ( $all_category_title ) { ?>
              	 		<li><a data-filter="*" href="#" class="product-btn current"><?php echo esc_html( $all_category_title ); ?></a></li>
                 	<?php }
									if ($product_categories) {
									$item_count = 0;
									foreach ($product_categories as $key => $category) {
										$product_show_category = $category['product_show_category'];
										$category_title = $category['category_title'] ? $category['category_title'] : $product_show_category;
										if ($product_show_category) {
											$item_count++;
											if ($item_count == 1) {
												$active_class = 'current';
											} else{
												$active_class = '';
											}
										?>
										<li><a data-filter=".<?php echo preg_replace('/\s+/', "", strtolower($product_show_category)); ?>" href="#" class="product-btn">
												<?php echo esc_html($category_title); ?>
											</a>
										</li>
										<?php
										}
									}
								}
								?>
							</ul>
						</div>
						<div class="gallery-container gallery-fancybox masonry-gallery row">
            	<?php while($wco_products_list->have_posts()){
            		$wco_products_list->the_post(); 
            		global $product;
            		$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average   = $product->get_average_rating();
            		$regular_price  = $product->get_regular_price();
            		$current_price  = $product->get_price();
            		if( $regular_price > $current_price ){
            			$total_off = $regular_price - $current_price;
            			$total_off = ($total_off/$regular_price) * 100;
            			$total_off = round($total_off);
            		} else {
            			$total_off = '';
            		}
            		$terms = wp_get_post_terms(get_the_ID(),'product_cat');
            		$cat_class = '';
            		foreach ($terms as $key => $term) {
            			$cat_class .= $term->slug.' ';
            		}

			          $wco_filter_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'wco-product-filter', false, '' );
							  $wco_filter_alt = get_post_meta($wco_filter_image, '_wp_attachment_image_alt', true);
			          
            		?>
                <div class="col-lg-3 col-md-6 col-12 <?php echo esc_attr( $cat_class ); ?> custom-grid zoomIn" data-wow-duration="2000ms">
                  <div class="wco-product-single">
                      <div class="wco-product-item">
                          <div class="wco-product-img">
                            <a href="<?php the_permalink(); ?>">
                            	<img src="<?php echo esc_url( $wco_filter_image[0] ); ?>" alt="<?php echo esc_attr( $wco_filter_alt ); ?>">
                            </a>
                          </div>
                      </div>
                      <div class="wco-product-text">
                         <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                          <div class="product-price">
                            <ul>
                                <li><?php if ( $price_html = $product->get_price_html() ) : ?>
                                	<span class="price">
                                		<?php echo wp_kses( $price_html, array('del' => array(),'span' => array(),'bdi' => array() ) ); ?>
                                		</span>
                                	<?php endif; ?>
                                </li>
                            </ul>
                          </div>
                      </div>
                      <div class="wco-product-text-hide">
                         <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                          <div class="product-price">
                            <ul>
                                <li><?php if ( $price_html = $product->get_price_html() ) : ?>
                                	<span class="price">
                                		<?php echo wp_kses( $price_html, array('del' => array(),'span' => array(),'bdi' => array() ) ); ?>
                                		</span>
                                	<?php endif; ?>
                                </li>
                            </ul>
                          </div>
                            <?php
														echo apply_filters(
															'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
															sprintf(
																'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
																esc_url( $product->add_to_cart_url() ),
																esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
																esc_attr( isset( $args['class'] ) ? $args['class'].' cart-btn' : 'cart-btn' ),
																isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
																esc_html( $product->add_to_cart_text() )
															),
															$product,
															$args
														);
                          ?>
                      </div>
                  </div>
              </div>
            	<?php } wp_reset_postdata(); ?>
          	</div>
        	</div>
        </div>
      </div>
    </div>
	</div>
	<!-- Flash-Sale-area-end -->
	<?php 
	}
	// Return outbut buffer
	echo ob_get_clean();
		
	}
	/**
	 * Render Title widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	*/
	
	//protected function _content_template(){}
	
}
Plugin::instance()->widgets_manager->register( new WCoIsotopFilter_Product_Filter() );