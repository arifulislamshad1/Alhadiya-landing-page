<?php 
// Initialize device tracking and create session ID
if (get_theme_mod('enable_device_tracking', true)) {
    $device_info = track_enhanced_device_info();
    $session_id = $device_info['session_id'] ?? null;
} else {
    $session_id = null;
}
?>
<?php get_header(); ?>

<div class="container">
    <!-- Logo Section -->
    <div class="logo">
        <?php if (has_custom_logo()): ?>
            <?php the_custom_logo(); ?>
        <?php else: ?>
            <h1 style="color: #dd0055; font-size: 32px; margin: 15px 0; font-family: 'SolaimanLipi', Arial, sans-serif;">
                <?php echo esc_html(get_bloginfo('name')); ?>
            </h1>
        <?php endif; ?>
    </div>

    <!-- Main Heading -->
    <h1 class="main-heading">
        <?php echo esc_html(get_theme_mod('main_heading_text', 'অর্গানিক হাতের মেহেদি বানানোর কোর্স মাত্র ৪৯০ টাকা')); ?>
    </h1>
    
    <!-- Video Section -->
    <?php 
    $youtube_video_url = get_theme_mod('youtube_video_url', '');
    if ($youtube_video_url) {
        $embed_url = get_youtube_embed_url($youtube_video_url);
        if ($embed_url) {
    ?>
    <div class="video-container" id="video-section">
        <div class="video-wrapper">
            <iframe id="youtube-video-player" src="<?php echo esc_url($embed_url); ?>?enablejsapi=1" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
    <?php 
        }
    }
    ?>
    
    <!-- Course Details - Now Dynamic with Individual Colors -->
    <section class="Corse_container" id="course-section-1" style="--section-color: <?php echo get_theme_mod('section1_color', '#28a745'); ?>">
        <h3 style="color: var(--section-color);"><?php echo esc_html(get_theme_mod('section1_title', '🌱 অর্গানিক মেহেদী তৈরির সহজ উপায়')); ?></h3>
        <div class="Corse_dtail">
            <ul class="Corse_dtail_left">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item1_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item1_text', 'অর্গানিক হাতের মেহেদী তৈরি')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item2_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item2_text', 'ড্রাই রিলিজ কিভাবে করে')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item3_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item3_text', 'মেহেদী কোণ তৈরি')); ?>
                </li>
            </ul>
            <ul class="Corse_dtail_right">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item4_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item4_text', 'প্রফেশনাল রেসিপি শিট')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item5_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item5_text', 'কি কি তেল ব্যবহার করা যাবে')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item6_color', '#28a745')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item6_text', 'কিভাবে মেহেদির রঙ গাড় হবে (সিক্রেট টিপস)')); ?>
                </li>
            </ul>
        </div>
    </section>
    
    <section class="Corse_container" id="course-section-2" style="--section-color: <?php echo get_theme_mod('section2_color', '#dc3545'); ?>">
        <h3 style="color: var(--section-color);"><?php echo esc_html(get_theme_mod('section2_title', '🔥 মেহেদী রঙ বাড়ানোর গোপন টিপস')); ?></h3>
        <div class="Corse_dtail">
            <ul class="Corse_dtail_left">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item7_color', '#dc3545')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item7_text', 'মেহেদির মূল্য নির্ধারণ')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item8_color', '#dc3545')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item8_text', 'দীর্ঘদিন সংরক্ষণ কিভাবে করবেন')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item9_color', '#dc3545')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item9_text', 'মেহেদী কিভাবে বিক্রি করবেন')); ?>
                </li>
            </ul>
            <ul class="Corse_dtail_right">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item10_color', '#dc3545')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item10_text', 'সার্টিফিকেট প্রদান')); ?>
                </li>
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item11_color', '#dc3545')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item11_text', 'মেহেদী কোণ প্যাকেজিং')); ?>
                </li>
            </ul>
        </div>
    </section>
   
    <section class="Corse_container" id="course-section-3" style="--section-color: <?php echo get_theme_mod('section3_color', '#6f42c1'); ?>">
        <h3 style="color: var(--section-color);"><?php echo esc_html(get_theme_mod('section3_title', '📦 প্যাকেজিং ও সার্টিফিকেশন')); ?></h3>
        <div class="Corse_dtail">
            <ul class="Corse_dtail_left">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item12_color', '#6f42c1')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item12_text', 'প্যাকেজিং ডিজাইন ও লেবেলিং')); ?>
                </li>
            </ul>
            <ul class="Corse_dtail_right">
                <li style="color: <?php echo esc_attr(get_theme_mod('course_item13_color', '#6f42c1')); ?>;">
                    <?php echo esc_html(get_theme_mod('course_item13_text', 'বিক্রির জন্য প্রস্তুতি')); ?>
                </li>
            </ul>
        </div>
    </section>
    
    <a href="#order" class="btn btn-primary btn-lg" id="order-button-top">এখানে অর্ডার করুন</a><br>
    
    <h2 class="title mt-3"><?php echo esc_html(get_theme_mod('review_heading', 'কাস্টমার রিভিউ')); ?></h2>
</div>

<!-- Customer Reviews Slider -->
<div class="review-slider" id="review-section">
    <div class="container">
        <div class="swiper reviewSwiper">
            <div class="swiper-wrapper">
                <?php
                $reviews = new WP_Query(array(
                    'post_type' => 'course_review',
                    'posts_per_page' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($reviews->have_posts()) :
                    while ($reviews->have_posts()) : $reviews->the_post();
                        $customer_name = get_post_meta(get_the_ID(), '_customer_name', true);
                        $customer_rating = get_post_meta(get_the_ID(), '_customer_rating', true);
                ?>
                <div class="swiper-slide">
                    <div class="review-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'review-image', 'alt' => get_the_title())); ?>
                        <?php endif; ?>
                        
                        <?php if ($customer_name || $customer_rating || get_the_content()) : ?>
                        <div class="review-content">
                            <h5 class="customer-name"><?php echo esc_html($customer_name); ?></h5>
                            
                            <?php if ($customer_rating) : ?>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="fa<?php echo $i <= $customer_rating ? 's' : 'r'; ?> fa-star text-warning"></i>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (get_the_content()) : ?>
                                <div class="review-text">
                                    <?php the_content(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <div class="swiper-slide">
                    <div class="review-card">
                        <div class="review-content">
                            <p style="color: white;">কোন রিভিউ পাওয়া যায়নি। অনুগ্রহ করে অ্যাডমিন প্যানেল থেকে Customer Reviews যোগ করুন।</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <a href="#order" class="btn btn-primary btn-lg" id="order-button-middle">এখানে অর্ডার করুন</a><br>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq" id="faq-section">
    <div class="container">
        <h2 class="title mt-5"><?php echo esc_html(get_theme_mod('faq_heading', 'প্রশ্ন ও উত্তর')); ?></h2>
    </div>
</div>

<div class="faq_section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="accordion accordion-flush" id="accordionExample">
                    <?php
                    $faqs = new WP_Query(array(
                        'post_type' => 'course_faq',
                        'posts_per_page' => 10,
                        'orderby' => 'menu_order',
                        'order' => 'ASC'
                    ));
                    
                    if ($faqs->have_posts()) :
                        $i = 1;
                        while ($faqs->have_posts()) : $faqs->the_post();
                            $i++;
                    ?>
                    <div class="accordion-item shadow-sm">
                        <h2 class="accordion-header" id="heading<?php echo $i; ?>">
                            <button class="accordion-button bg-transparent fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
                                <?php the_title(); ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $i; ?>" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endwhile;
                        wp_reset_postdata();
                    else:
                    ?>
                    <div class="col-12">
                        <p style="color: white;">কোন FAQ পাওয়া যায়নি। অনুগ্রহ করে অ্যাডমিন প্যানেল থেকে FAQs যোগ করুন।</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Section -->
<div class="order_section" id="order">
    <div class="container">
        <form id="wc-order-form" method="POST" action="">
            <?php wp_nonce_field('alhadiya_nonce', 'nonce'); ?>
            <input type="hidden" name="submit_wc_order" value="1">
            
            <h2 class="title" style="font-size:24px">প্যাকেজটি সিলেক্ট করুনঃ</h2><br>
            
            <div class="product_desk">
                <div id="msg"></div>
                <div class="row mt-2 product-row">
                    <?php
                    if (class_exists('WooCommerce')) {
                        $products = wc_get_products(array(
                            'status' => 'publish',
                            'limit' => -1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        ));
                        
                        if (!empty($products)) :
                            $first = true;
                            foreach ($products as $product) :
                                $product_id = $product->get_id();
                                $regular_price = $product->get_regular_price();
                                $sale_price = $product->get_sale_price();
                                $price = $product->get_price();
                    ?>
                    <div class="col-lg-4 col-md-6 col-6">
                        <label class="labels">
                            <input type="radio" class="products_id" name="product_id" value="<?php echo esc_attr($product_id); ?>" id="pro_id<?php echo esc_attr($product_id); ?>" <?php echo $first ? 'checked' : ''; ?> required>
                            <div class="products_dets">
                                <span class="checkmark"><i class="fa-solid fa-check"></i></span>
                                <div class="img_preview">
                                    <?php echo $product->get_image('medium', array('class' => 'img-fluid product-image')); ?>
                                </div>
                                <div class="product_description">
                                    <h2><?php echo esc_html($product->get_name()); ?></h2>
                                    <div class="price">
                                        <?php if ($sale_price && $regular_price != $sale_price) : ?>
                                            <p><del>৳ <?php echo esc_html($regular_price); ?></del></p>
                                            <p class="alex-mt"><strong style="color: #dd0055;">৳ <?php echo esc_html($sale_price); ?></strong></p>
                                        <?php else : ?>
                                            <p class="alex-mt"><strong style="color: #dd0055;">৳ <?php echo esc_html($price); ?></strong></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php 
                                $first = false;
                            endforeach;
                        else:
                    ?>
                    <div class="col-12">
                        <p style="color: white;">কোন প্রোডাক্ট পাওয়া যায়নি। অনুগ্রহ করে WooCommerce থেকে প্রোডাক্ট যোগ করুন।</p>
                    </div>
                    <?php 
                        endif;
                    } else {
                    ?>
                    <div class="col-12">
                        <p style="color: white;">WooCommerce প্লাগইন সক্রিয় নেই। অনুগ্রহ করে WooCommerce ইনস্টল এবং সক্রিয় করুন।</p>
                    </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="order_details">
                <div class="row">
                    <h3 class="order_title">Billing & Shipping</h3>
                    <div class="col-lg-6 order-lg-1">
                        <input type="text" name="billing_first_name" class="form-control mb-3" placeholder="আপনার নাম লিখুনঃ" required>
                        <input type="tel" name="billing_phone" class="form-control mb-3" placeholder="আপনার মোবাইল নাম্বার লিখুনঃ" required>
                        <textarea name="billing_address_1" class="form-control mb-3" placeholder="আপনার ঠিকানা লিখুনঃ" rows="3" required></textarea>
                        
                        <!-- Delivery Options -->
                        <div class="delivery-options">
                            <label class="delivery-options-label">🚚 ডেলিভারি অপশন:</label>
                            <div class="delivery-options-container">
                                <div class="delivery-option">
                                    <input type="radio" id="dhaka" name="delivery_zone" value="1" checked required>
                                    <label for="dhaka"><?php echo esc_html(get_theme_mod('dhaka_delivery_title', 'ঢাকার মধ্যে ডেলিভারি')); ?> - ৳<?php echo esc_html(get_theme_mod('dhaka_delivery_charge', 0)); ?></label>
                                </div>
                                <div class="delivery-option">
                                    <input type="radio" id="outside_dhaka" name="delivery_zone" value="2" required>
                                    <label for="outside_dhaka"><?php echo esc_html(get_theme_mod('outside_dhaka_delivery_title', 'ঢাকার বাইরে ডেলিভারি')); ?> - ৳<?php echo esc_html(get_theme_mod('outside_dhaka_delivery_charge', 0)); ?></label>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods - Updated Order: Pay Later first as default -->
                        <div class="payment-methods">
                            <label class="payment-methods-label">💳 পেমেন্ট পদ্ধতি:</label>
                            <div class="payment-methods-container">
                                <div class="payment-method">
                                    <input type="radio" id="pay_later" name="payment_method" value="pay_later" checked required>
                                    <label for="pay_later">
                                        <span class="payment-icon pay-later-icon">⏰</span>
                                        <?php if (get_theme_mod('show_payment_text', true)) : ?>
                                            <span class="payment-text">Pay Later</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="bkash" name="payment_method" value="bkash" required>
                                    <label for="bkash">
                                        <?php 
                                        $bkash_icon = get_theme_mod('bkash_icon');
                                        if ($bkash_icon) {
                                            echo '<img src="' . esc_url(wp_get_attachment_url($bkash_icon)) . '" alt="bKash" class="payment-icon-img">';
                                        } else {
                                            echo '<span class="payment-icon bkash-icon">📱</span>';
                                        }
                                        ?>
                                        <?php if (get_theme_mod('show_payment_text', true)) : ?>
                                            <span class="payment-text">bKash</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="nagad" name="payment_method" value="nagad" required>
                                    <label for="nagad">
                                        <?php 
                                        $nagad_icon = get_theme_mod('nagad_icon');
                                        if ($nagad_icon) {
                                            echo '<img src="' . esc_url(wp_get_attachment_url($nagad_icon)) . '" alt="Nagad" class="payment-icon-img">';
                                        } else {
                                            echo '<span class="payment-icon nagad-icon">💰</span>';
                                        }
                                        ?>
                                        <?php if (get_theme_mod('show_payment_text', true)) : ?>
                                            <span class="payment-text">Nagad</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="rocket" name="payment_method" value="rocket" required>
                                    <label for="rocket">
                                        <?php 
                                        $rocket_icon = get_theme_mod('rocket_icon');
                                        if ($rocket_icon) {
                                            echo '<img src="' . esc_url(wp_get_attachment_url($rocket_icon)) . '" alt="Rocket" class="payment-icon-img">';
                                        } else {
                                            echo '<span class="payment-icon rocket-icon">🚀</span>';
                                        }
                                        ?>
                                        <?php if (get_theme_mod('show_payment_text', true)) : ?>
                                            <span class="payment-text">Rocket</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="payment-instructions" id="payment-instructions" style="display: block;">
                            <div class="payment-instruction" id="pay_later-instruction" style="display: block;">
                                <div class="instruction-header">
                                    <h4 style="color: #6c757d;">⏰ Pay Later</h4>
                                </div>
                                <p>Pay Later - Pay at delivery (ডেলিভারির সময় টাকা দিবেন)</p>
                            </div>

                            <div class="payment-instruction" id="bkash-instruction">
                                <div class="instruction-header">
                                    <h4 style="color: <?php echo esc_attr(get_theme_mod('bkash_color', '#e2136e')); ?>;">📱 bKash পেমেন্ট</h4>
                                    <div class="number-copy">
                                        <span id="bkash-number"><?php echo esc_html(get_theme_mod('bkash_number', '01975669946')); ?></span>
                                        <button type="button" class="copy-btn" onclick="copyNumber('bkash-number')">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <p><?php echo esc_html(get_theme_mod('bkash_instruction', 'আর এই নাম্বারে বিকাশ সেন্ডমানি করে ফর্ম এ লিখুন (Personal)')); ?></p>
                                <input type="text" name="transaction_number" class="form-control transaction-input" placeholder="ট্রানজেকশন নাম্বার লিখুন">
                            </div>

                            <div class="payment-instruction" id="nagad-instruction">
                                <div class="instruction-header">
                                    <h4 style="color: <?php echo esc_attr(get_theme_mod('nagad_color', '#f47920')); ?>;">💰 Nagad পেমেন্ট</h4>
                                    <div class="number-copy">
                                        <span id="nagad-number"><?php echo esc_html(get_theme_mod('nagad_number', '01737146996')); ?></span>
                                        <button type="button" class="copy-btn" onclick="copyNumber('nagad-number')">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <p><?php echo esc_html(get_theme_mod('nagad_instruction', 'আর এই নাম্বারে নগদে সেন্ডমানি করে ফর্ম এ লিখুন (Personal)')); ?></p>
                                <input type="text" name="transaction_number" class="form-control transaction-input" placeholder="ট্রানজেকশন নাম্বার লিখুন">
                            </div>

                            <div class="payment-instruction" id="rocket-instruction">
                                <div class="instruction-header">
                                    <h4 style="color: <?php echo esc_attr(get_theme_mod('rocket_color', '#8b1538')); ?>;">🚀 Rocket পেমেন্ট</h4>
                                    <div class="number-copy">
                                        <span id="rocket-number"><?php echo esc_html(get_theme_mod('rocket_number', '01737146996')); ?></span>
                                        <button type="button" class="copy-btn" onclick="copyNumber('rocket-number')">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <p><?php echo esc_html(get_theme_mod('rocket_instruction', 'আর এই নাম্বারে রকেটে সেন্ডমানি করে ফর্ম এ লিখুন (Personal)')); ?></p>
                                <input type="text" name="transaction_number" class="form-control transaction-input" placeholder="ট্রানজেকশন নাম্বার লিখুন">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-2">
                        <div class="invoice-bills" style="display:none">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="w-60">Description</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="name_cart"></td>
                                            <td id="price_cart">৳<span class="price_cart"></span></td>
                                            <td>1</td>
                                            <td>৳<span class="price_cart"></span></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2"><b>Subtotal</b></td>
                                            <td>৳<span class="price_cart"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2"><b>Delivery Charge</b></td>
                                            <td>৳<span id="delivery_charge">0</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2"><b>Total</b></td>
                                            <td><b>৳<span id="total_amount"></span></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Order Button Moved Here - Below Payment Instructions -->
                    <div class="col-12 order-lg-3">
                        <div class="order-button-section" style="margin-top: 25px; text-align: center;">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-order-btn">🛒 অর্ডার করুন</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Floating Contact Buttons -->
<a href="tel:<?php echo esc_attr(get_theme_mod('phone_number', '+8801737146996')); ?>" class="callbtnlaptop">
    <i class="fas fa-phone"></i>
</a>

<a href="https://wa.me/88<?php echo esc_attr(get_theme_mod('whatsapp_number', '01737146996')); ?>" target="_blank" class="float">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Enhanced Invoice Modal -->
<div class="invoice-modal" id="invoice-modal">
    <div class="invoice-content">
        <div class="invoice-header">
            <button class="invoice-close" onclick="closeInvoiceModal()">&times;</button>
            <div class="invoice-success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="invoice-title">অর্ডার সফল!</h2>
            <p class="invoice-subtitle">আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে</p>
        </div>
        <div class="invoice-body">
            <div class="invoice-section">
                <h4>অর্ডার বিবরণ</h4>
                <div class="invoice-details">
                    <div class="invoice-row">
                        <span>অর্ডার নাম্বার:</span>
                        <span id="invoice-order-id">-</span>
                    </div>
                    <div class="invoice-row">
                        <span>কাস্টমার নাম:</span>
                        <span id="invoice-customer-name">-</span>
                    </div>
                    <div class="invoice-row">
                        <span>মোবাইল নাম্বার:</span>
                        <span id="invoice-customer-phone">-</span>
                    </div>
                    <div class="invoice-row">
                        <span>পেমেন্ট পদ্ধতি:</span>
                        <span id="invoice-payment-method">-</span>
                    </div>
                    <div class="invoice-row">
                        <span>ট্রানজেকশন নাম্বার:</span>
                        <span id="invoice-transaction-number">-</span>
                    </div>
                    <div class="invoice-row">
                        <span>মোট পরিমাণ:</span>
                        <span id="invoice-total-amount">-</span>
                    </div>
                </div>
            </div>
            
            <div class="invoice-actions">
                <button class="invoice-btn invoice-btn-primary" onclick="closeInvoiceModal()">
                    <i class="fas fa-check"></i> ঠিক আছে
                </button>
                <a href="tel:<?php echo esc_attr(get_theme_mod('phone_number', '+8801737146996')); ?>" class="invoice-btn invoice-btn-success">
                    <i class="fas fa-phone"></i> কল করুন
                </a>
                <a href="https://wa.me/88<?php echo esc_attr(get_theme_mod('whatsapp_number', '1737146996')); ?>" target="_blank" class="invoice-btn invoice-btn-secondary">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Load YouTube IFrame Player API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
var videoPlayStartTime;
var videoCurrentTime = 0;
var videoDuration = 0;
var videoTrackingEnabled = <?php echo get_theme_mod('enable_video_tracking', true) ? 'true' : 'false'; ?>;

// Global tracking settings
var deviceTrackingEnabled = <?php echo get_theme_mod('enable_device_tracking', true) ? 'true' : 'false'; ?>;
var customEventsTrackingEnabled = <?php echo get_theme_mod('enable_custom_events_tracking', true) ? 'true' : 'false'; ?>;
var deviceDetailsTrackingEnabled = <?php echo get_theme_mod('enable_device_details_tracking', true) ? 'true' : 'false'; ?>;
var timeSpentTrackingEnabled = <?php echo get_theme_mod('enable_time_spent_tracking', true) ? 'true' : 'false'; ?>;

// Session ID from PHP
var phpSessionId = '<?php echo $session_id ?: ''; ?>';

console.log('Tracking Settings:', {
    deviceTrackingEnabled,
    customEventsTrackingEnabled,
    deviceDetailsTrackingEnabled,
    timeSpentTrackingEnabled
});
console.log('PHP Session ID:', phpSessionId);

// Helper functions (always available)
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function getSessionId() {
    // First try PHP session ID, then cookie
    if (phpSessionId && phpSessionId !== '') {
        console.log('Using PHP session ID:', phpSessionId);
        return phpSessionId;
    }
    
    const sessionId = getCookie('device_session');
    console.log('All cookies:', document.cookie);
    console.log('Looking for device_session cookie...');
    console.log('Session ID found:', sessionId);
    return sessionId;
}

function trackCustomEvent(eventType, eventName, eventValue = '') {
    if (!customEventsTrackingEnabled || typeof ajax_object === 'undefined') {
        console.log('Event tracking disabled or ajax_object not available');
        return;
    }
    const sessionId = getSessionId();
    if (!sessionId) {
        console.log('No session ID found');
        return;
    }

    console.log('Tracking event:', { eventType, eventName, eventValue });

    jQuery.post(ajax_object.ajax_url, {
        action: 'track_custom_event',
        session_id: sessionId,
        event_type: eventType,
        event_name: eventName,
        event_value: eventValue,
        nonce: ajax_object.event_nonce
    }).done(function(response) {
        console.log('Event tracked successfully:', response);
    }).fail(function(xhr, status, error) {
        console.error('Failed to track event:', error);
    });
}

function onYouTubeIframeAPIReady() {
    const videoElement = document.getElementById('youtube-video-player');
    if (videoElement) {
        player = new YT.Player('youtube-video-player', {
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }
}

function onPlayerReady(event) {
    videoDuration = player.getDuration();
    if (videoTrackingEnabled) {
        trackCustomEvent('video_ready', 'YouTube Video Ready', `Duration: ${videoDuration.toFixed(2)}s`);
    }
}

function onPlayerStateChange(event) {
    if (!videoTrackingEnabled) return;
    
    if (event.data == YT.PlayerState.PLAYING) {
        if (!videoPlayStartTime) {
            videoPlayStartTime = Date.now();
        }
        trackCustomEvent('video_play', 'YouTube Video Play', `Current Time: ${player.getCurrentTime().toFixed(2)}s`);
    } else if (event.data == YT.PlayerState.PAUSED) {
        if (videoPlayStartTime) {
            const timeSpent = (Date.now() - videoPlayStartTime) / 1000;
            trackCustomEvent('video_play_time', 'YouTube Video Time Spent', timeSpent.toFixed(2));
            videoPlayStartTime = null; // Reset
        }
        trackCustomEvent('video_pause', 'YouTube Video Pause', `Current Time: ${player.getCurrentTime().toFixed(2)}s`);
    } else if (event.data == YT.PlayerState.ENDED) {
        if (videoPlayStartTime) {
            const timeSpent = (Date.now() - videoPlayStartTime) / 1000;
            trackCustomEvent('video_play_time', 'YouTube Video Time Spent', timeSpent.toFixed(2));
            videoPlayStartTime = null; // Reset
        }
        trackCustomEvent('video_ended', 'YouTube Video Ended', `Total Duration: ${videoDuration.toFixed(2)}s`);
    } else if (event.data == YT.PlayerState.BUFFERING) {
        trackCustomEvent('video_buffering', 'YouTube Video Buffering', `Current Time: ${player.getCurrentTime().toFixed(2)}s`);
    }
}

// Initialize device details tracking
async function initializeDeviceDetailsTracking() {
    console.log('Initializing device details tracking...');
    
    if (!deviceDetailsTrackingEnabled || typeof ajax_object === 'undefined') {
        console.log('Device details tracking disabled or ajax_object not available');
        return;
    }
    
    const sessionId = getSessionId();
    if (!sessionId) {
        console.log('No session ID found for device details');
        return;
    }

    console.log('Session ID found:', sessionId);

    const deviceInfo = {
        session_id: sessionId,
        language: navigator.language || 'N/A',
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || 'N/A',
        connection_type: navigator.connection ? navigator.connection.effectiveType : 'N/A',
        memory_info: navigator.deviceMemory || 'N/A',
        cpu_cores: navigator.hardwareConcurrency || 'N/A',
        touchscreen_detected: ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0) ? 1 : 0,
        nonce: ajax_object.device_info_nonce
    };

    console.log('Device info collected:', deviceInfo);

    // Battery Info (async)
    if ('getBattery' in navigator) {
        try {
            const battery = await navigator.getBattery();
            deviceInfo.battery_level = battery.level;
            deviceInfo.battery_charging = battery.charging ? 1 : 0;

            console.log('Battery info:', { level: battery.level, charging: battery.charging });

            // Track battery changes as custom events
            battery.addEventListener('chargingchange', () => {
                trackCustomEvent('battery_status_change', 'Battery Charging Status Changed', JSON.stringify({
                    level: battery.level,
                    charging: battery.charging
                }));
            });
            battery.addEventListener('levelchange', () => {
                trackCustomEvent('battery_status_change', 'Battery Level Changed', JSON.stringify({
                    level: battery.level,
                    charging: battery.charging
                }));
            });

        } catch (e) {
            console.warn('Battery API not available or permission denied:', e);
            deviceInfo.battery_level = null;
            deviceInfo.battery_charging = null;
        }
    } else {
        console.log('Battery API not available');
        deviceInfo.battery_level = null;
        deviceInfo.battery_charging = null;
    }

    // Send device info to server
    console.log('Sending device info to server...');
    jQuery.post(ajax_object.ajax_url, {
        action: 'update_client_device_details',
        ...deviceInfo
    }, function(response) {
        if (response.success) {
            console.log('Client device info updated successfully:', response.data);
        } else {
            console.error('Failed to update client device info:', response.data);
        }
    }).fail(function(xhr, status, error) {
        console.error('AJAX failed for device details:', error);
    });

    // Send screen size
    const screenWidth = window.screen.width;
    const screenHeight = window.screen.height;
    const screenSize = `${screenWidth}x${screenHeight}`;

    console.log('Screen size:', screenSize);

    jQuery.post(ajax_object.ajax_url, {
        action: 'update_device_screen_size',
        session_id: sessionId,
        screen_size: screenSize,
        nonce: ajax_object.screen_size_nonce
    }, function(response) {
        if (response.success) {
            console.log('Screen size updated successfully:', screenSize);
        } else {
            console.error('Failed to update screen size:', response.data);
        }
    }).fail(function(xhr, status, error) {
        console.error('AJAX failed for screen size:', error);
    });
}

// Initialize time spent tracking
function initializeTimeSpentTracking() {
    console.log('Initializing time spent tracking...');
    
    if (!timeSpentTrackingEnabled || typeof ajax_object === 'undefined') {
        console.log('Time spent tracking disabled or ajax_object not available');
        return;
    }
    
    var startTime = Date.now();
    var sessionId = getSessionId();
    
    function updateTimeSpent() {
        var timeSpent = Math.floor((Date.now() - startTime) / 1000);
        
        if (sessionId && timeSpent > 10) { // Only update if more than 10 seconds
            console.log('Updating time spent:', timeSpent + 's');
            fetch(ajax_object.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_time_spent&session_id=' + sessionId + '&time_spent=' + timeSpent + '&nonce=' + ajax_object.device_info_nonce
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Time spent updated successfully');
                } else {
                    console.error('Failed to update time spent:', data.data);
                }
            }).catch(error => {
                console.error('Error updating time spent:', error);
            });
        }
    }
    
    // Update time spent every 30 seconds
    setInterval(updateTimeSpent, 30000);
    
    // Update on page unload
    window.addEventListener('beforeunload', updateTimeSpent);
}

// Initialize device tracking
function initializeDeviceTracking() {
    console.log('Initializing device tracking...');
    
    // Track page load as an event
    trackCustomEvent('page_load', 'Page Loaded', window.location.pathname);
    
    // Track initial page view
    trackCustomEvent('page_view', 'Page Viewed', window.location.pathname);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    console.log('ajax_object available:', typeof ajax_object !== 'undefined');
    if (typeof ajax_object !== 'undefined') {
        console.log('ajax_object:', ajax_object);
    }
    
    // Check for session ID immediately
    const initialSessionId = getSessionId();
    console.log('Initial session ID check:', initialSessionId);
    
    // Initialize Swiper with enhanced autoplay
    const swiper = new Swiper('.reviewSwiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    
    // Initialize tracking based on settings
    if (deviceTrackingEnabled) {
        console.log('Device tracking enabled, initializing...');
        initializeDeviceTracking();
        
        if (timeSpentTrackingEnabled) {
            initializeTimeSpentTracking();
        }
        
        if (deviceDetailsTrackingEnabled) {
            // Delay device details initialization to ensure everything is loaded
            setTimeout(function() {
                console.log('Delayed device details initialization...');
                const delayedSessionId = getSessionId();
                console.log('Delayed session ID check:', delayedSessionId);
                initializeDeviceDetailsTracking();
            }, 2000); // Increased delay to 2 seconds
        }
    } else {
        console.log('Device tracking disabled');
    }
    
    // Track custom events if enabled
    if (customEventsTrackingEnabled) {
        console.log('Custom events tracking enabled');
        
        // Track button clicks
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn, button, a[href="#order"]')) {
                trackCustomEvent('button_click', 'Button Click', e.target.textContent.trim() || e.target.getAttribute('href') || 'Unknown Button');
            }
        });
        
        // Track form interactions
        const orderForm = document.getElementById('wc-order-form');
        if (orderForm) {
            orderForm.addEventListener('submit', function(e) {
                trackCustomEvent('form_submit', 'Order Form Submit', 'Order Form Submitted');
            });
        }
        
        // Track Swiper interactions
        swiper.on('slideChange', function () {
            trackCustomEvent('swiper_slide_change', 'Swiper Slide Changed', 'Slide ' + (this.realIndex + 1));
        });
        
        // Track FAQ accordion clicks
        document.querySelectorAll('.accordion-button').forEach(button => {
            button.addEventListener('click', function() {
                const faqTitle = this.textContent.trim();
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                trackCustomEvent('faq_toggle', isExpanded ? 'FAQ Closed' : 'FAQ Opened', faqTitle);
            });
        });
        
        // Track scroll depth
        let lastScrollDepth = 0;
        window.addEventListener('scroll', function() {
            const scrollY = window.scrollY;
            const docHeight = document.documentElement.scrollHeight;
            const windowHeight = window.innerHeight;
            const currentScrollDepth = Math.min(100, (scrollY / (docHeight - windowHeight)) * 100);

            if (Math.abs(currentScrollDepth - lastScrollDepth) >= 10) {
                trackCustomEvent('scroll_depth', 'Page Scroll Depth', currentScrollDepth.toFixed(2));
                lastScrollDepth = currentScrollDepth;
            }
        });
    } else {
        console.log('Custom events tracking disabled');
    }
    
    // Product selection and price calculation
    const productRadios = document.querySelectorAll('input[name="product_id"]');
    const deliveryRadios = document.querySelectorAll('input[name="delivery_zone"]');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    
    // Initialize with first product
    if (productRadios.length > 0) {
        updateProductInfo(productRadios[0].value);
    }
    
    // Product selection handler
    productRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateProductInfo(this.value);
                // GA4 Event: select_content
                if (typeof gtag === 'function') {
                    gtag('event', 'select_content', {
                        content_type: 'product',
                        item_id: this.value,
                        item_name: document.querySelector(`#pro_id${this.value} + .products_dets .product_description h2`).textContent,
                        value: parseFloat(document.querySelector(`#pro_id${this.value} + .products_dets .price .alex-mt strong`).textContent.replace('৳ ', '')),
                        currency: 'BDT'
                    });
                }
                // FB Pixel Event: ViewContent (or AddToCart if user intends to buy)
                if (typeof fbq === 'function') {
                    fbq('track', 'ViewContent', {
                        content_ids: [this.value],
                        content_name: document.querySelector(`#pro_id${this.value} + .products_dets .product_description h2`).textContent,
                        content_type: 'product',
                        value: parseFloat(document.querySelector(`#pro_id${this.value} + .products_dets .price .alex-mt strong`).textContent.replace('৳ ', '')),
                        currency: 'BDT'
                    });
                }
                trackCustomEvent('product_select', 'Product Selected', `Product ID: ${this.value}, Name: ${document.querySelector(`#pro_id${this.value} + .products_dets .product_description h2`).textContent}`);
            }
        });
    });
    
    // Delivery zone change handler
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateTotalAmount();
            trackCustomEvent('delivery_option_select', 'Delivery Option Selected', this.id);
        });
    });
    
    // Payment method change handler
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            showPaymentInstructions(this.value);
            trackCustomEvent('payment_method_select', 'Payment Method Selected', this.value);
        });
    });
    
    // Initialize payment instructions
    showPaymentInstructions('pay_later');
    
    function updateProductInfo(productId) {
        // Get product data via AJAX
        if (typeof jQuery !== 'undefined' && typeof ajax_object !== 'undefined') {
            jQuery.post(ajax_object.ajax_url, {
                action: 'get_wc_product_data',
                id: productId,
                nonce: ajax_object.nonce
            }, function(response) {
                if (response && response.name) {
                    document.querySelector('.name_cart').textContent = response.name;
                    document.querySelectorAll('.price_cart').forEach(el => {
                        el.textContent = response.price;
                    });
                    updateTotalAmount();
                    document.querySelector('.invoice-bills').style.display = 'block';
                }
            });
        }
    }
    
    function updateTotalAmount() {
        const selectedDelivery = document.querySelector('input[name="delivery_zone"]:checked');
        const productPrice = parseFloat(document.querySelector('.price_cart')?.textContent) || 0;
        
        let deliveryCharge = 0;
        if (selectedDelivery && typeof ajax_object !== 'undefined') {
            if (selectedDelivery.value === '1') {
                deliveryCharge = parseFloat(ajax_object.dhaka_delivery_charge) || 0;
            } else if (selectedDelivery.value === '2') {
                deliveryCharge = parseFloat(ajax_object.outside_dhaka_delivery_charge) || 0;
            }
        }
        
        const totalAmount = productPrice + deliveryCharge;
        
        const deliveryChargeEl = document.getElementById('delivery_charge');
        const totalAmountEl = document.getElementById('total_amount');
        
        if (deliveryChargeEl) deliveryChargeEl.textContent = deliveryCharge;
        if (totalAmountEl) totalAmountEl.textContent = totalAmount;
    }
    
    function showPaymentInstructions(method) {
        // Hide all instructions and remove required from their transaction inputs
        document.querySelectorAll('.payment-instruction').forEach(instruction => {
            instruction.style.display = 'none';
            const transactionInput = instruction.querySelector('input[name="transaction_number"]');
            if (transactionInput) {
                transactionInput.removeAttribute('required');
                transactionInput.value = ''; // Clear value when hidden
            }
        });
        
        // Show selected instruction and set required for its transaction input if needed
        const selectedInstruction = document.getElementById(method + '-instruction');
        if (selectedInstruction) {
            selectedInstruction.style.display = 'block';
            const transactionInput = selectedInstruction.querySelector('input[name="transaction_number"]');
            if (transactionInput && method !== 'pay_later') {
                transactionInput.setAttribute('required', 'required');
            }
        }
    }
    
    // Track form field interactions
    document.querySelectorAll('input[name="billing_first_name"], input[name="billing_phone"], textarea[name="billing_address_1"]').forEach(input => {
        input.addEventListener('focus', function() {
            trackCustomEvent('form_field_focus', 'Form Field Focused', this.name);
        });
        input.addEventListener('change', function() {
            trackCustomEvent('form_field_change', 'Form Field Changed', `${this.name}: ${this.value.substring(0, 50)}`);
        });
    });

    // Enhanced form submission with better error handling
    const orderForm = document.getElementById('wc-order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-order-btn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> প্রসেসিং...';
            
            const formData = new FormData(this);
            formData.append('action', 'submit_wc_order');
            
            // GA4 Event: begin_checkout
            if (typeof gtag === 'function') {
                const items = [{
                    item_id: formData.get('product_id'),
                    item_name: document.querySelector(`#pro_id${formData.get('product_id')} + .products_dets .product_description h2`).textContent,
                    price: parseFloat(document.querySelector(`#pro_id${formData.get('product_id')} + .products_dets .price .alex-mt strong`).textContent.replace('৳ ', '')),
                    quantity: 1
                }];
                gtag('event', 'begin_checkout', {
                    currency: 'BDT',
                    value: parseFloat(document.getElementById('total_amount').textContent),
                    items: items
                });
            }

            // FB Pixel Event: InitiateCheckout
            if (typeof fbq === 'function') {
                fbq('track', 'InitiateCheckout', {
                    content_ids: [formData.get('product_id')],
                    content_type: 'product',
                    value: parseFloat(document.getElementById('total_amount').textContent),
                    currency: 'BDT'
                });
            }
            trackCustomEvent('order_form_submit', 'Order Form Submitted');

            if (typeof ajax_object !== 'undefined') {
                fetch(ajax_object.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store form data for invoice
                        const customerName = formData.get('billing_first_name');
                        const customerPhone = formData.get('billing_phone');
                        
                        showInvoiceModal({
                            ...data.data,
                            customer_name: customerName,
                            customer_phone: customerPhone
                        });
                        
                        this.reset();
                        // Reset to first product
                        if (productRadios.length > 0) {
                            productRadios[0].checked = true;
                            updateProductInfo(productRadios[0].value);
                        }
                        // Reset to Pay Later
                        const payLaterRadio = document.getElementById('pay_later');
                        if (payLaterRadio) {
                            payLaterRadio.checked = true;
                            showPaymentInstructions('pay_later');
                        }
                        trackCustomEvent('order_success', 'Order Placed Successfully', `Order ID: ${data.data.order_id}`);

                        // Save form data to localStorage for auto-fill
                        localStorage.setItem('billing_first_name', customerName);
                        localStorage.setItem('billing_phone', customerPhone);
                        localStorage.setItem('billing_address_1', formData.get('billing_address_1'));

                    } else {
                        alert(data.data || 'অর্ডার প্রসেসিং এ সমস্যা হয়েছে');
                        trackCustomEvent('order_failure', 'Order Submission Failed', data.data || 'Unknown error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('অর্ডার প্রসেসিং এ সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
                    trackCustomEvent('order_error', 'Order Submission Error', error.message);
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
        });
    }

    // Track order buttons clicks
    document.getElementById('order-button-top')?.addEventListener('click', function() {
        trackCustomEvent('button_click', 'Order Button Clicked', 'Top Order Button');
    });
    document.getElementById('order-button-middle')?.addEventListener('click', function() {
        trackCustomEvent('button_click', 'Order Button Clicked', 'Middle Order Button');
    });
    document.getElementById('submit-order-btn')?.addEventListener('click', function() {
        trackCustomEvent('button_click', 'Order Button Clicked', 'Submit Order Button');
    });

    // Auto-fill form fields from localStorage
    const billingFirstNameInput = document.querySelector('input[name="billing_first_name"]');
    const billingPhoneInput = document.querySelector('input[name="billing_phone"]');
    const billingAddressInput = document.querySelector('textarea[name="billing_address_1"]');

    if (billingFirstNameInput && localStorage.getItem('billing_first_name')) {
        billingFirstNameInput.value = localStorage.getItem('billing_first_name');
    }
    if (billingPhoneInput && localStorage.getItem('billing_phone')) {
        billingPhoneInput.value = localStorage.getItem('billing_phone');
    }
    if (billingAddressInput && localStorage.getItem('billing_address_1')) {
        billingAddressInput.value = localStorage.getItem('billing_address_1');
    }

    // Send screen size to server once per session
    const sessionId = getSessionId();
    if (sessionId && typeof jQuery !== 'undefined' && typeof ajax_object !== 'undefined') {
        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;
        const screenSize = `${screenWidth}x${screenHeight}`;

        // Check if screen size is already stored in session (or a temporary cookie) to avoid redundant updates
        if (!sessionStorage.getItem('screen_size_sent_' + sessionId)) {
            jQuery.post(ajax_object.ajax_url, {
                action: 'update_device_screen_size',
                session_id: sessionId,
                screen_size: screenSize,
                nonce: ajax_object.screen_size_nonce // Use the new nonce
            }, function(response) {
                if (response.success) {
                    sessionStorage.setItem('screen_size_sent_' + sessionId, 'true');
                }
            });
        }
    }
});

function copyNumber(elementId) {
    const numberElement = document.getElementById(elementId);
    if (!numberElement) return;
    
    const number = numberElement.textContent;
    
    navigator.clipboard.writeText(number).then(function() {
        // Show success feedback
        const button = numberElement.nextElementSibling;
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.style.background = '#28a745';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '#dd0055';
            }, 2000);
        }
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('কপি করতে সমস্যা হয়েছে। নাম্বারটি ম্যানুয়ালি কপি করুন: ' + number);
    });
}

function showInvoiceModal(data) {
    const orderIdEl = document.getElementById('invoice-order-id');
    const customerNameEl = document.getElementById('invoice-customer-name');
    const customerPhoneEl = document.getElementById('invoice-customer-phone');
    const paymentMethodEl = document.getElementById('invoice-payment-method');
    const transactionNumberEl = document.getElementById('invoice-transaction-number');
    const totalAmountEl = document.getElementById('invoice-total-amount');
    
    if (orderIdEl) orderIdEl.textContent = '#' + (data.order_id || 'N/A');
    if (customerNameEl) customerNameEl.textContent = data.customer_name || 'N/A';
    if (customerPhoneEl) customerPhoneEl.textContent = data.customer_phone || 'N/A';
    if (paymentMethodEl) paymentMethodEl.textContent = data.payment_method || 'N/A';
    if (transactionNumberEl) transactionNumberEl.textContent = data.transaction_number || 'N/A';
    if (totalAmountEl) {
        const totalAmount = document.getElementById('total_amount');
        totalAmountEl.textContent = '৳' + (totalAmount ? totalAmount.textContent : '0');
    }
    
    const modal = document.getElementById('invoice-modal');
    if (modal) modal.classList.add('show');
}

function closeInvoiceModal() {
    const modal = document.getElementById('invoice-modal');
    if (modal) modal.classList.remove('show');
}

// Close modal when clicking outside
const invoiceModal = document.getElementById('invoice-modal');
if (invoiceModal) {
    invoiceModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeInvoiceModal();
        }
    });
}

// Ensure time spent for visible sections and buttons is sent on page unload
window.addEventListener('beforeunload', function() {
    // Track time spent for any currently visible sections
    for (const sectionId in sectionTimers) {
        if (sectionTimers.hasOwnProperty(sectionId)) {
            const timeSpent = (Date.now() - sectionTimers[sectionId]) / 1000;
            // Use navigator.sendBeacon for reliable data transmission on unload
            if (navigator.sendBeacon) {
                const formData = new FormData();
                formData.append('action', 'track_custom_event');
                formData.append('session_id', getSessionId()); // Use the new getSessionId()
                formData.append('event_type', 'section_time_spent');
                formData.append('event_name', `Time Spent on Section: ${sectionId} (Unload)`);
                formData.append('event_value', `${timeSpent.toFixed(2)}s`);
                formData.append('nonce', ajax_object.event_nonce); // Use the new nonce
                navigator.sendBeacon(ajax_object.ajax_url, formData);
            } else {
                // Fallback for older browsers (less reliable on unload)
                trackCustomEvent('section_time_spent', `Time Spent on Section: ${sectionId} (Unload)`, `${timeSpent.toFixed(2)}s`);
            }
        }
    }

    // Track time spent for any currently visible floating buttons
    for (const buttonName in floatingButtonTimers) {
        if (floatingButtonTimers.hasOwnProperty(buttonName)) {
            const timeSpent = (Date.now() - floatingButtonTimers[buttonName]) / 1000;
            if (navigator.sendBeacon) {
                const formData = new FormData();
                formData.append('action', 'track_custom_event');
                formData.append('session_id', getSessionId()); // Use the new getSessionId()
                formData.append('event_type', 'button_time_spent');
                formData.append('event_name', `Time Spent on ${buttonName} (Unload)`);
                formData.append('event_value', `${timeSpent.toFixed(2)}s`);
                formData.append('nonce', ajax_object.event_nonce); // Use the new nonce
                navigator.sendBeacon(ajax_object.ajax_url, formData);
            } else {
                // Fallback for older browsers
                trackCustomEvent('button_time_spent', `Time Spent on ${buttonName} (Unload)`, `${timeSpent.toFixed(2)}s`);
            }
        }
    }
});
</script>

<?php get_footer(); ?>
