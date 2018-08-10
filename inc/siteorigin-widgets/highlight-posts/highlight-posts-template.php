<?php
$query_args = newsroom_pb_parse_query($instance['posts']);
$query_args['posts_per_page'] = 1;
$query_args['without_map_query'] = 1;
$query_args['post__not_in'] = $GLOBALS['excluded_post'];
$query_args['meta_query'] = array(array('key' => '_thumbnail_id'));
$highlight_post = new WP_Query($query_args);
if($highlight_post->have_posts()) :
  $highlight_post->the_post();
  echo '<h2>' . $instance['title'] . '</h2>';
  $post_id = get_the_ID();
  array_push($GLOBALS['excluded_post'] , $post_id);
  ?>
  <div class="newsroom-highlight-posts-<?php echo $instance['style'] ?>">
    <article id="<?php echo $instance['panels_info']['id']; ?>-highlight-posts-<?php the_ID(); ?>">
      <?php if(has_post_thumbnail()) : ?>
        <div class="highlight-posts-thumbnail">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(array(320,320)); ?></a>
        </div>
      <?php endif; ?>
      <div class="highlight-posts-post-content">
        <?php
        $kicker = wp_get_post_terms(get_the_ID(), 'pub_type', array('fields' => 'names'));
        if ($kicker[0] != '') {
            echo '<p class="kicker">'. $kicker[0] .'</p>';
        }
        ?>
        <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <p class="date"><?php echo get_the_date(); ?></p>
        <?php
        $pub_name = get_post_meta( get_the_ID(), 'pub_name', true);
        echo '<p class="dateline">' . $pub_name . '</p>';
        ?>
      </div>
    </article>
  </div>
  <div class="newsroom-highlight-headline-posts">
    <ul class="highlight-posts-posts">
    <?php
    $query_args = newsroom_pb_parse_query($instance['posts']);
    $query_args['posts_per_page'] = intval($instance['per_row']);
    $query_args['without_map_query'] = 1;
    $query_args['post__not_in'] = $GLOBALS['excluded_post'];
    $highlight_posts_query = new WP_Query($query_args);
    while($highlight_posts_query->have_posts()) :
      $highlight_posts_query->the_post();
      $post_id = get_the_ID();
      array_push($GLOBALS['excluded_post'] , $post_id);
      ?>
      <li class="highlight-posts-item">
        <article id="<?php echo $instance['panels_info']['id']; ?>-highlight-posts-<?php the_ID(); ?>">
          <div class="highlight-posts-post-content">
            <a href="<?php the_permalink(); ?>" class="headline" title="<?php the_title(); ?>"><h2><?php the_title(); ?></h2></a>
            <p class="date"><?php echo get_the_date(); ?>
            <?php
              $pub_name = get_post_meta( get_the_ID(), 'pub_name', true);
              echo '<p class="dateline">' . $pub_name . '</p>';
            ?>
          </div>
        </article>
      </li>
      <?php
      wp_reset_postdata();
      endwhile;
      ?>
    </ul>
  </div>
<?php endif;?>
