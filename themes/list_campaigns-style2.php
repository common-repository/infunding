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
while ($query->have_posts()) :
    $query->the_post();
    $campaignInfo = $utility->getCampaignInfo(get_the_ID());
    ?>
    <div class="post_item">
        <div class="item-info">
            <div class="iw-row">
                <div class="iw-col-md-4 iw-col-sm-12 iw-col-xs-12">
                    <div class="image">
                        <?php
                        $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'infunding-thumb');
                        echo '<img src="' . $img[0] . '" alt=""/>';
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
                    </div>
                </div>
                <div class="iw-col-md-8 iw-col-sm-12 iw-col-xs-12">
                    <div class="campaign-info">
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
                            <?php if ($show_location) { ?>
                                <div class="campaign-location theme-color">
                                    <i class="fa fa-map-marker"></i>
                                    <?php
                                    echo esc_attr($campaignInfo->address);
                                    ?>
                                </div>
                                <?php
                            }
                            if ($show_des) {
                                echo '<div class="campaign-des">' . esc_textarea($utility->truncateString(get_the_excerpt(), $desc_text_limit)) . '</div>';
                            }
                            ?>
                        </div>
                        <?php
                        if ($show_progress):
                            ?>
                            <div class="campaing-progress">
                                <?php
                                if ($campaignInfo->goal):
                                    echo '<div class="camp-funded">' . sprintf(__('<span>Donated</span> %s&percnt;', 'inwavethemes'), $campaignInfo->percent) . '</div>';
                                    echo '<div class="camp-progress-goal"><div class="camp-progress-current theme-bg" style="width:' . ($campaignInfo->percent > 100 ? 100 : $campaignInfo->percent) . '%;"></div></div>';
                                endif;
                                ?>
                                <div class="camp-timeremaining-goal">
                                    <div class="donates-info-item">
                                        <span class="iw-capital-label"><?php _e('Raised', 'inwavethemes'); ?></span>
                                        <span class="iw-capital-value"><?php $utility->getMoneyFormated(number_format($campaignInfo->current, 0), $campaignInfo->currency); ?></span>
                                    </div>
                                    <?php if ($campaignInfo->goal): ?>
                                        <div class="donates-info-item">
                                            <span class="iw-capital-label"><?php echo sprintf(__('Goal', 'inwavethemes')); ?></span>
                                            <span class="iw-capital-value"><?php $utility->getMoneyFormated(number_format($campaignInfo->goal, 0), $campaignInfo->currency); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="donate-btn iw-button-effect">
                                        <div class="donate-btn-effect">
                                            <button data-id ="<?php echo get_the_ID(); ?>" data-external-link="<?php echo esc_url($campaignInfo->external_link); ?>" class="iw-capital donate <?php
                                            echo!$campaignInfo->status ? 'disable' : 'theme-bg enable';
                                            ?>"><span  data-hover="<?php echo __('Donate', 'inwavethemes'); ?>" class="effect"><?php echo __('Donate', 'inwavethemes'); ?></span></button>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>

                            </div>
                        <?php endif; ?>
                        <div class="donate-from">
                            <?php
                            if ($campaignInfo->status && !$campaignInfo->external_link) {
                                echo '<div id="donate-form-' . $campaignInfo->id . '" style="display:none;">';
                                echo do_shortcode('[infunding_donate_form campaign="' . $campaignInfo->id . '" fields="full_name,email,address,phone" popup=1]');
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
endwhile;
wp_reset_postdata();
