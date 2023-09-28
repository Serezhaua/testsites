<?php get_header();?>

<div id="post-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px">
    <?php
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 2, 
        'order' => 'ASC',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post(); ?>
            <div style="padding: 20px; border: 1px solid silver">
                <?php the_title(); ?>
                <?php echo wp_trim_words(get_the_content(), 15); ?>
                <a href="<?php the_permalink(); ?>">Переглянути</a>
            </div>
        <?php }
        wp_reset_postdata();
    } ?>
</div>
<button id="load-more">Показать еще</button>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    var page = 1; 
    var postsContainer = document.getElementById('post-container');
    var loadMoreButton = document.getElementById('load-more');

    loadMoreButton.addEventListener('click', function() {
        loadMoreButton.disabled = true; 

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/wp-admin/admin-ajax.php?action=load_more_posts&page=' + page, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.success) {
                    page++;
                    loadMoreButton.disabled = false; /

                    if (response.data) {
                       
                      postsContainer.innerHTML += response.data;
                    }
                }
            }
        };
        xhr.send();
    });
});

</script>

<?php get_footer();?>
