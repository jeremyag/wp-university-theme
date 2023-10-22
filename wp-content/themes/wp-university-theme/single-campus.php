<?php 
    get_header();
    while(have_posts()) {
        the_post(); 
        pageBanner(); ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("campus"); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> 
                    <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
            <div class="generic-content">
                <?php the_content(); ?>
            </div>

            <?php 
                $today = date("Ymd");

                $relatedPrograms = new WP_Query([
                    "posts_per_page" => -1,
                    "post_type" => "program",
                    "orderby" => "title",
                    "order" => "ASC",
                    "meta_query" => [
                        [
                            "key" => "related_campus",
                            "compare" => "LIKE",
                            "value" => '"'.get_the_ID().'"'
                        ]
                    ]
                ]);

                if($relatedPrograms->have_posts()){ ?>
                    <hr class="section-break"/>
                    <h2 class="headline headline--medium">Programs Available At This Campus</h2>
                    <ul class="min-list link-list">
                    <?php
                        while($relatedPrograms->have_posts()){
                            $relatedPrograms->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </li>
                        <?php } wp_reset_postdata(); ?>
                    </ul>
                <?php }
                
                $homePageEvents = new WP_Query([
                    "posts_per_page" => 2,
                    "post_type" => "event",
                    "meta_key" => "event_date",
                    "orderby" => "meta_value_num",
                    "order" => "ASC",
                    "meta_query" => [
                        [
                            "key" => "event_date",
                            "compare" => ">=",
                            "value" => $today,
                            "type" => "numeric"
                        ],
                        [
                            "key" => "related_programs",
                            "compare" => "LIKE",
                            "value" => '"'.get_the_ID().'"'
                        ]
                    ]
                ]);

                if($homePageEvents->have_posts()){ ?>
                    <hr class="section-break"/>
                    <h2 class="headline headline--medium">Upcoming <?php echo get_the_title(); ?> Events</h2>
                    <?php
                        while($homePageEvents->have_posts()){
                            $homePageEvents->the_post(); 
                            get_template_part("template-parts/content", "event");
                        }
                        wp_reset_postdata();
                }
            ?>
        </div>
    <?php }
    get_footer();
?>