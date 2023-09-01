<?php 
/* Template Name: Template Events */
get_header();

$args = array(
    'post_type'      => 'event',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

$events = new WP_Query($args);
?>

<main>
    <div class="container">
        <div class="filter">
            <?php $terms = get_terms(['taxonomy'=>'category']);
            if($terms) : ?>
                <div class="select-wrapper">
                    <select name="cat" id="cat">
                        <option value="">Select country</option>
                        <?php foreach ($terms as $term) : ?>
                            <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ( $events->have_posts() ) : ?>
                <?php while ($events->have_posts()) : $events->the_post(); ?>   
                    <?php $event_year = get_field('event_year'); ?>
                <?php endwhile; ?>
            <?php endif; ?>
            
            <div class="date-wrapper">
                <div class="year">
                    <div class="select">Select Year:</div>
                    <?php 
                    $years = array(); 
                    while ($events->have_posts()) : $events->the_post();
                        $event_year = get_field('event_year');
                        if (!in_array($event_year, $years)) {
                            $years[] = $event_year;
                        }
                    endwhile;

                    foreach ($years as $year) : ?>
                        <div class="checkbox">
                            <label>
                                <input class="pristine" type="checkbox" name="year" value="<?php echo $year; ?>">
                                <span><?php echo $year; ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="month">
                    <div class="select">Select Month:</div>
                    <?php 
                    $months = array(); 
                    while ($events->have_posts()) : $events->the_post();
                        $event_month = get_field('event_month');
                        if (!in_array($event_month, $months)) {
                            $months[] = $event_month;
                        }
                    endwhile;
                    foreach ($months as $month) : ?>
                        <div class="checkbox">
                            <label>
                                <input class="pristine" type="checkbox" name="month" value="<?php echo $month; ?>">
                                <span><?php echo $month; ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php if ( $events->have_posts() ) : ?>
        <div class="event_container">
            <?php while ($events->have_posts()) : $events->the_post(); ?>   
                <?php get_template_part('template/event-loop', 'event'); ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
