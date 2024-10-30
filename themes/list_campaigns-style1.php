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
if ($number_column > 1) {
    ?>
    <div class="iw-row">
        <?php
    }
    while ($query->have_posts()) :
        $query->the_post();
        $campaignInfo = $utility->getCampaignInfo(get_the_ID());
        ?>
        <div class="iw-col-sm-<?php echo 12 / (($number_column - 1) > 0 ? $number_column - 1 : 1); ?> iw-col-md-<?php echo 12 / $number_column; ?> iw-col-xs-12 post_item">
            <div class="item-info">
                <div class="image">
                    <?php
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'infunding-thumb');
                    echo '<img src="' . $img[0] . '" alt=""/>';
                    if ($show_location):
                        ?>
                        <div class="control-overlay">
                            <div class="campaign-location">
                                <span><i class="fa fa-map-marker"></i></span>
                                <span>
                                    <?php
                                    echo esc_attr($campaignInfo->address);
                                    ?>
                                </span>
                            </div>
                        </div>
                        <?php
                    endif;
                    $time_class = 'lower';
                    if ($campaignInfo->time_end) {
                        if ($campaignInfo->days_to_end <= 15 && $campaignInfo->days_to_end > 7) {
                            $time_class = 'medium';
                        } elseif ($campaignInfo->days_to_end <= 7 && $campaignInfo->days_to_end > 0) {
                            $time_class = 'height';
                        } else if ($campaignInfo->days_to_end <= 0) {
                            $time_class = 'ended';
                        }
                    }
                    ?>
                    <?php if ($show_icon_action): ?>
                        <div class="iw-icon-action">
                            <div class="icon-detail"><a class="theme-color-hover" href="<?php echo get_permalink(); ?>"><i class="fa fa-arrow-right"></i></a></div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="campaign-text">
                    <div class="campaign-title">
                        <div class="title">
                            <h3><a class="theme-color-hover" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </div>
                        <?php if ($show_time_icon): ?>
                            <div class="timer">
                                <span class="clock-icon <?php echo esc_attr($time_class); ?>"><i class="fa fa-clock-o"></i></span>
                            </div>
                        <?php endif; ?>
                        <div style="clear: both;"></div>
                    </div>
                    <?php
                    if ($show_des) {
                        echo '<div class="campaign-des">' . $utility->truncateString(get_the_excerpt(), $desc_text_limit) . '</div>';
                    }
                    ?>
                </div>
                <?php
                if ($show_progress):
                    ?>
                    <div class="campaing-progress">
                        <?php
                        if ($campaignInfo->goal):
                            echo '<div class="camp-funded">' . sprintf(__('%s&percnt; funded', 'inwavethemes'), number_format($campaignInfo->percent,2)) . '</div>';
                            echo '<div class="camp-progress-goal theme-bg"><div class="camp-progress-current" style="width:' . ($campaignInfo->percent > 100 ? 100 : $campaignInfo->percent) . '%;"></div></div>';
                        endif;
                        echo '<div class="camp-timeremaining-goal"><span class="camp-timeremaining">' . ($campaignInfo->days_to_start >= 0 ? sprintf(__('%s day(s) to start', 'inwavethemes'), $campaignInfo->days_to_start) : ($campaignInfo->time_end ? ($campaignInfo->days_to_end >= 0 ? sprintf(__('%s day(s) to end', 'inwavethemes'), $campaignInfo->days_to_end) : __('ENDED', 'inwavethemes')) : __('In Progress', 'inwavethemes'))) . '</span>' . ($campaignInfo->goal ? '<span class="camp-goal">' . sprintf(__('Goal: %s', 'inwavethemes'), number_format($campaignInfo->goal, 0, ',', '.')) . '</span>' : '') . '</div>'
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    if ($number_column > 1) {
        ?>
        <div style="clear: both;"></div>
    </div>
    <?php
}
