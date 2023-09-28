<?php

function load_more_posts() {
    $page = $_GET['page'];
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 2,
        'order' => 'ASC',
        'paged' => $page,
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div style="padding: 20px; border: 1px solid silver">
                <?php the_title(); ?>
                <?php echo wp_trim_words(get_the_content(), 15); ?>
                <a href="<?php the_permalink(); ?>">Переглянути</a>
            </div>
            <?php
        }
        wp_reset_postdata();
    }

    $data = ob_get_clean();

    if ($query->max_num_pages > $page) {
        $response = array(
            'success' => true,
            'data' => $data,
        );
    } else {
        $response = array(
            'success' => false,
        );
    }

    wp_send_json($response);
}


add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
