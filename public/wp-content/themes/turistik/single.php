<?php get_header();?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="content">
        <div class="single-content">
        <h1 class="title-page"><?php the_title();?></h1>
        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="Image" class="alignleft">
        <?php the_content();?>
        </div>
<?php endwhile; else:?>
<p><?php _e('Ничего не найдено');?></p>
<?php endif;?>
        <ol>
            <li>
                При заполнении формы бронирования/покупки внимательно
                вписывайте все свои данные. После того, как все заполните, перепроверьте:
                все номера личных документов до цифр, имя, фамилию - до букв, чтобы
                все было указано точь-в-точь, как в вашем загранпаспорте.
            </li>
            <li>
                Далее вам на e-mail придет "Подтверждение бронирования".
                Это еще не подтверждение покупки, а просто сведения о бронировании!
            </li>
            <li>
                Вторым электронным документом, пришедшим вам на e-mail,
                будет "Информация о платеже" - она говорит о том, что деньги сняты
                с вашей карты и переведены в авиакомпанию.
            </li>
        </ol>
        <div class="page-navigation">
            <div class="page-navigation-wrap"><a href="#" class="page-navigation__prev-page"><i class="icon icon-angle-double-left"></i>Предыдущая статья</a></div>
            <div class="page-navigation-wrap"><a href="#" class="page-navigation__next-page">Сдедующая статья<i class="icon icon-angle-double-right"></i></a></div>
        </div>
    </div>

<?php get_footer();?>