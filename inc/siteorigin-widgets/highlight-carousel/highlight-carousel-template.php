<?php
$query_args = newsroom_pb_parse_query($instance['posts']);
$query_args['posts_per_page'] = 6;
$query_args['without_map_query'] = 1;
$query_args['meta_query'] = array(array('key' => '_thumbnail_id'));
$query_args['post__not_in'] = $GLOBALS['excluded_post'];
$query_args['tax_query'] = array(
array(
  'taxonomy' => 'pub_type',
  'field'    => 'slug',
  'terms'    => array( 'new-digest' ),
  'operator' => 'NOT IN',
));
$highlight_query = new WP_Query($query_args);
if($highlight_query->have_posts()) :
  ?>
  <div class="newsroom-highlight-carousel" data-rotate="<?php echo $instance['rotate'] ? 1 : 0; ?>" data-rotate-delay="<?php echo $instance['rotate_delay']; ?>">
    <ul class="highlight-carousel-posts">
    <?php
    while($highlight_query->have_posts()) :
      $highlight_query->the_post();
      $post_id = get_the_ID();
      // by mohjak 2019-11-24 Fix Undefined offset: 0
      if (isset($GLOBALS['excluded_post']) && $GLOBALS['excluded_post']) {
        array_push($GLOBALS['excluded_post'] , $post_id);
      }
      $data_set_post = get_post_meta( get_the_ID(), 'dataset_content', true);
        if ($data_set_post == '1') {
          $tracking = 'onclick="trackOutboundLink(\''. get_the_permalink() .'\');"';
        }else {
          $tracking = '';
        }
      ?>
      <li class="highlight-carousel-item">
        <article id="highlight-carousel-<?php the_ID(); ?>">
          <div class="highlight-carousel-thumbnail">
            <?php the_post_thumbnail('highlight-carousel'); ?>
          </div>

          <div class="highlight-carousel-post-content">
            <h2>
              <a href="<?php the_permalink(); ?>" <?php echo $tracking; ?> title="<?php the_title(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php if (get_post_meta(get_the_ID(), 'is_label', true) == "1"): ?>
            <br><a href="#"><span class="label">Belt and Road</span></a>
            <?php endif; ?>
            <p class="date"><?php echo get_the_date(); ?></p>
            <?php the_excerpt(); ?>
          </div>
        </article>
      </li>
      <?php
      wp_reset_postdata();
    endwhile;
    ?>
    </ul>
  </div>
  <?php
endif;
?>
