<?php
/*
 * @package Inwave Charity
 * @version 1.0.0
 * @created Nov 3, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://www.inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * Description of list_campaigns-style1
 *
 * @developer duongca
 */
 wp_enqueue_script('owl-carousel');
$owl_options = array(
    "items" => 1,
    "itemsCustom" => false,
    "itemsDesktop" => array(1199, 1),
    "itemsDesktopSmall" => array(980, 1),
    "itemsTablet" => array(768, 1),
    "itemsTabletSmall" => false,
    "itemsMobile" => array(479, 1),
    "singleItem" => false,
    "itemsScaleUp" => false,
    //Autoplay
    "autoPlay" => false,
    "stopOnHover" => false,
    // Navigation
    "navigation" => true,
    "navigationText" => array("", ""),
    "rewindNav" => true,
    "scrollPerPage" => false,
    //Pagination
    "pagination" => false,
    "paginationNumbers" => false
);
?>
<div class="iw-campaign-listing-slider iw-campaign-listing-slider-v2 owl-carousel" data-plugin-options="<?php echo htmlspecialchars(json_encode($owl_options)); ?>">
    <?php
    while ($query->have_posts()) :
        $query->the_post();
        ?>
        <div class="iw-campaign-slider-item">
            <div class="item-info-warp">
                <div class="iw-row">
                    <div class="iw-campaign-image iw-col-md-6 iw-col-sm-12 iw-col-xs-12">
                        <?php
                        $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'infunding-large');
                        echo '<div class="campaign-image" style="background-image: url(' . $img[0] . ');"></div>';
                        if ($show_location):
                            ?>
                            <div class="control-overlay">
                                <div class="campaign-location">
                                    <span><i class="fa fa-map-marker"></i></span>
                                    <span>
                                        <?php
                                        echo htmlspecialchars(get_post_meta(get_the_ID(), 'inf_address', true));
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <?php
                        endif;
                        $time_end = htmlspecialchars(get_post_meta(get_the_ID(), 'inf_end_date', true));
                        $time_class = 'lower';
                        if ($time_end) {
                            $days = floor(($time_end - time()) / 86400);
                            if ($days <= 15 && $days > 7) {
                                $time_class = 'medium';
                            } elseif ($days <= 7 && $days > 0) {
                                $time_class = 'height';
                            } else if ($days <= 0) {
                                $time_class = 'ended';
                            }
                        }
                        ?>
                    </div>
                    <div class="campaign-info iw-col-md-7 iw-col-sm-12 iw-col-xs-12">
                        <div class="campaign-info-content">
                            <div class="iw-block-title">
                                <h3><?php echo __('Featured campaigns', 'inwavethemes') ?></h3>
                            </div>
                            <div class="campaign-title">
                                <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                            </div>
                            <?php
                            if ($show_des) {
                                echo '<div class="campaign-des">' . $utility->truncateString(get_the_excerpt(), $desc_text_limit) . '</div>';
                            }
                            ?>
                            <?php
                            if ($show_progress):
                                ?>
                                <div class="campaing-progress">
                                    <?php
                                    $current = htmlspecialchars(get_post_meta(get_the_ID(), 'inf_current', true));
                                    $goal = htmlspecialchars(get_post_meta(get_the_ID(), 'inf_goal', true));
                                    if ($goal) {
                                        $percent = $current / $goal * 100;

                                        echo '<div class="camp-donated">' . __('donated', 'inwavethemes') . '</div>';
                                        echo '<div class="camp-funded">' . $percent . '%</div>';
                                        echo '<div class="camp-progress-goal"><div class="camp-progress-current theme-bg" style="width:' . ($percent > 100 ? 100 : $percent) . '%;"></div></div>';
                                    }
                                    echo '<div class="camp-timeremaining-button">
                                    <div class="camp-timeremaining-goal-current">
                                    <div class="camp-current"><span class="text">' . __('raised', 'inwavethemes') . '</span><span class="current">' . $utility->getMoneyFormated(number_format($current, 0, ',', '.'),get_post_meta(get_the_ID(), 'inf_currency', true)) . '</span></div>';
                                    if ($goal) {
                                        echo '<div class="camp-goal"><span class="text">' . __('goal', 'inwavethemes') . '</span><span class="goal">' . $utility->getMoneyFormated(number_format($goal, 0, ',', '.'),get_post_meta(get_the_ID(), 'inf_currency', true)) . '</span></div>';
                                    }
                                    echo '</div>
                                    <div class="iw-donate-detail iw-button-effect">
                                        <a class="ibutton-effect1 theme-bg" href="' . get_permalink() . '"><span class="effect" data-hover="' . __('donate', 'inwavethemes') . '">' . __('donate', 'inwavethemes') . '</span></a>
                                    </div>
                                    <div style="clear: both;"></div>
                                    </div>'
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    ?>
</div>