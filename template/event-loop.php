<a class="item" href="<?php the_permalink(); ?>">
    <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
    <?php endif; ?>
    <div class="item_description">
        <h2><?php the_title(); ?></h2>
        <?php 
        $cats = get_the_terms(get_the_ID(), 'category');
        $year = get_field('event_year');
        $month = get_field('event_month');
        if (!empty($cats)) : ?>
            <ul>
                <?php if (!empty($cats)): ?>
                    <li>
                        <strong>Location:</strong>
                        <?php foreach ($cats as $cat): ?>
                            <span><?php echo $cat->name; ?></span>
                        <?php endforeach; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($year)): ?>
                    <li>
                        <strong>Year:</strong>
                        <?php echo $year; ?>
                    </li>
                <?php endif; ?>
                <?php if (!empty($month)): ?>
                    <li>
                        <strong>Month:</strong>
                        <?php echo $month; ?>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>      
    </div>
</a>
