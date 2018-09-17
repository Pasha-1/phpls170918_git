<?php get_header(); ?>
    <div class="content">
        <?php
        $posts = query_posts([
            'post_type' => ['post', 'akcii'],
            'paged' => get_query_var('paged')
        ]);
        //    $posts = new WP_Query(['post_type' => ['post', 'akcii']]);
        //    foreach ($posts as $post) {
        //
        //    }
        ?>
        <h1 class="title-page">Последние новости и акции из мира туризма</h1>
        <div class="posts-list">
            <?php $date2 = ''; ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <!-- post-mini-->
                <div class="post-wrap">
                    <div class="post-thumbnail">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Image поста" class="post-thumbnail__image"></div>
                    <div class="post-content">
                        <div class="post-content__post-info">
                            <div class="post-date">
                                <?php
                                $date = get_the_date();
                                if (!empty($date)) {
                                    echo $date;
                                } else {
                                    $date2 = get_the_date();
                                }
                                ?>
                            </div>
                        </div>
                        <div class="post-content__post-text">
                            <div class="post-title">
                                <?php the_title(); ?>
                            </div>
                            <div>
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <div class="post-content__post-control">
                            <a href="<?php the_permalink(); ?>" class="btn-read-post">Читать далее >></a>
                        </div>
                    </div>
                </div>
                <!-- post-mini_end-->
            <?php endwhile; else: ?>
                <p><?php _e('Ничего не найдено.'); ?></p>
            <?php endif; ?>


        </div>
        <!--        <div class="pagenavi-post-wrap"><a href="#" class="pagenavi-post__prev-postlink"><i class="icon icon-angle-double-left"></i></a><span class="pagenavi-post__current">1</span><a href="#" class="pagenavi-post__page">2</a><a href="#" class="pagenavi-post__page">3</a><a href="#" class="pagenavi-post__page">...</a><a href="#" class="pagenavi-post__page">10</a><a href="#" class="pagenavi-post__next-postlink"><i class="icon icon-angle-double-right"></i></a></div>-->

        <?php the_posts_pagination(); ?>

        <?php wp_reset_query(); wp_reset_postdata(); ?>
    </div>
<?php get_footer(); ?>