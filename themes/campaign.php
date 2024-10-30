<?php
/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created Jun 4, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of campaign
 *
 * @developer duongca
 */
 
wp_enqueue_script('owl-carousel');
wp_enqueue_script('custombox');
wp_enqueue_script('google-maps');
wp_enqueue_script('markerclusterer');
wp_enqueue_script('infunding_map');
global $inf_settings;
$campaignInfo = $utility->getCampaignInfo(get_the_ID());
$owl_options = array(
    'navigation' => true, // Show next and prev buttons
    'slideSpeed' => 300,
    'paginationSpeed' > 400,
    'singleItem' => true,
    'pagination' => false,
    'navigationText' => array('<i class="fa fa-arrow-left"></i>', '<i class="fa fa-arrow-right"></i>')
);

?>
<div class="inf-wrap campaign-detail">
    <div class="infunding-main">
        <div class="iw-container">
            <?php
            $utility->getNoticeMessage();
            ?>
            <div class="iw-row">
                <div class="iw-col-md-8 main-content">
                    <div class="inf-sideshow">
                        <?php if (!empty($campaignInfo->images)): ?>
                            <div id="owl-iwevent" class="owl-carousel" data-plugin-options="<?php echo htmlspecialchars(json_encode($owl_options)); ?>">
                                <?php
                                foreach ($campaignInfo->images as $image) {
                                    $img = wp_get_attachment_image_src($image, 'infunding-large');
                                    echo '<div class="inf-slideshow-item"><img src="' . esc_url($img[0]) . '" alt=""/></div>';
                                }
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="feature-img">
                                <?php
                                $img_f = wp_get_attachment_image_src(get_post_thumbnail_id(), 'infunding-large');
                                echo '<img src="' . esc_url($img_f[0]) . '" />';
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="donates-info">
                        <?php if ($campaignInfo->goal): ?>
                            <div class="campaing-progress">
                                <?php
                                echo '<span class="current-donate theme-bg" style="margin-left:calc(' . ($campaignInfo->percent > 100 ? 100 : $campaignInfo->percent) . '% - 22px);">' . $campaignInfo->percent . '%</span>';
                                echo '<div class="camp-progress-goal"><div class="camp-progress-current theme-bg" style="width:' . ($campaignInfo->percent > 100 ? 100 : $campaignInfo->percent) . '%;"></div></div>';
                                ?>
                            </div>
                        <?php endif; ?>
                        <ul>
                            <li class="donates-info-item">
                                <span class="iw-capital-label"><?php _e('raised', 'inwavethemes'); ?></span>
                                <span class="iw-capital-value"><?php $utility->getMoneyFormated(number_format($campaignInfo->current, 0), $campaignInfo->currency); ?></span>
                            </li>
                            <?php if ($campaignInfo->goal): ?>
                                <li class="donates-info-item">
                                    <span class="iw-capital-label"><?php _e('Goal', 'inwavethemes'); ?></span>
                                    <span class="iw-capital-value"><?php $utility->getMoneyFormated(number_format($campaignInfo->goal, 0), $campaignInfo->currency); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ($campaignInfo->time_end): ?>
                                <li class="donates-info-item">
                                    <span class="iw-capital-label"><?php _e('Day left', 'inwavethemes'); ?></span>
                                    <span class="iw-capital-value"><?php echo ($campaignInfo->days_to_start >= 0 ? sprintf(__('%s day(s) to start', 'inwavethemes'), $campaignInfo->days_to_start) : ($campaignInfo->days_to_end >= 0 ? sprintf(__('%s day(s) to end', 'inwavethemes'), $campaignInfo->days_to_end) : __('ENDED', 'inwavethemes'))); ?></span>
                                </li>
                            <?php endif; ?>
                            <li class="donate-button">
                                <div class="donate-btn iw-button-effect">
                                    <div class="donate-btn-effect">
                                        <button data-id ="<?php echo get_the_ID(); ?>" data-external-link="<?php echo esc_url($campaignInfo->external_link); ?>" class="iw-capital donate <?php
                                        echo (!$campaignInfo->status ? 'disable' : 'theme-bg enable');
                                        ?>"><span  data-hover="<?php echo __('Donate', 'inwavethemes'); ?>" class="effect"><?php echo __('Donate', 'inwavethemes'); ?></span></button>
                                    </div>
                                    <?php
                                    if ($campaignInfo->status && !$campaignInfo->external_link) {
                                        echo '<div id="donate-form-' . $campaignInfo->id . '" style="display:none;">';
                                        echo apply_filters('the_content','[infunding_donate_form campaign="' . $campaignInfo->id . '" popup=1]');
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <h3 class="iw-capital title"><?php _e('Campaign description', 'inwavethemes') ?></h3>
                    <div class="campaign-des">
                        <?php the_content(); ?>
                    </div>
                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
                <div class="iw-col-md-4 campaign-detail-sidebar">
                    <div class="infunding-map">
                        <div class="infuding-map-wrap">
                            <div class="map-preview" style="height:300px;">
                            </div>
                        </div>
                        <script type="text/javascript">
                            (function ($) {
                                $(document).ready(function () {
                                    var options = {
                                        mapPlaces: [
                                            {
                                                "id": "pid-<?php echo get_the_ID(); ?>",
                                                "link": "<?php echo get_permalink(); ?>",
                                                "readmore": "Reard More",
                                                "title": "<?php echo get_the_title(); ?>",
                                                "image": "",
                                                "address": "<?php echo esc_attr($campaignInfo->address); ?>",
                                                "latitude": <?php echo isset($campaignInfo->map_pos['latitude']) && $campaignInfo->map_pos['latitude'] ? $campaignInfo->map_pos['latitude'] : 'null'; ?>,
                                                "longitude": <?php echo isset($campaignInfo->map_pos['longitude']) && $campaignInfo->map_pos['longitude'] ? $campaignInfo->map_pos['longitude'] : 'null'; ?>,
                                                "description": ""
                                            }
                                        ],
                                        mapProperties: {
                                            zoom: 8,
                                            center: new google.maps.LatLng(21.063997063246, 105.6184387207),
                                            zoomControl: false,
                                            scrollwheel: true,
                                            disableDoubleClickZoom: true,
                                            draggable: true,
                                            panControl: false,
                                            mapTypeControl: false,
                                            scaleControl: false,
                                            overviewMapControl: false,
                                            streetViewControl: false,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                        },
                                        detail_page: true,
                                        show_location: false,
                                        show_des: false,
                                        spinurl: "<?php echo plugins_url('infunding/assets/images/'); ?>",
                                        styleObj: {"name": "", "override_default": "1", "styles": ""}
                                    };
                                    $(".infunding-map").infMap(options);
                                });
                            })(jQuery);
                        </script>
                    </div>

                    <div class="sidebar-right campaign-info">
                        <h3 class="title iw-capital"><?php _e('Campaign Information', 'inwavethemes'); ?></h3>
                        <div class="campaign-info-ct">
                            <?php
                            $cats = array();
                            $tags = array();
                            if (!empty($campaignInfo->categories)) {
                                foreach ($campaignInfo->categories as $cat) {
                                    $term_link = get_term_link($cat);
                                    $cats[] = '<a href="' . esc_url($term_link) . '">' . $cat->name . '</a>';
                                }
                            }
                            if (!empty($campaignInfo->tags)) {
                                foreach ($campaignInfo->tags as $tag) {
                                    $tags[] = $tag->name;
                                }
                            }
                            ?>
                            <?php if (!empty($cats)): ?>
                                <div class="campaign-info-item">
                                    <label><?php echo __('Category'); ?></label>:<span class="campaign-info-value"><?php echo implode(', ', $cats); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="campaign-info-item">
                                <label><?php echo __('Cause status'); ?></label>:<span class="campaign-info-value"><?php echo ($campaignInfo->status ? __('In Progress', 'inwavethemes') : ($campaignInfo->days_to_start > 0 ? __('Upcoming', 'inwavethemes') : __('Ended', 'inwavethemes'))); ?></span>
                            </div>
                            <?php if (!empty($tags)): ?>
                                <div class="campaign-info-item">
                                    <label><?php echo __('Tags'); ?></label>:<span class="campaign-info-value"><?php echo implode(', ', $tags); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($campaignInfo->orders)) { ?>
                        <div class="sidebar-right donors">
                            <h3 class="title iw-capital"><?php _e('Latest donors', 'inwavethemes'); ?></h3>
                            <div class="donors-user">
                                <div class="donors-members">
                                    <?php
                                    foreach ($campaignInfo->orders as $ord) {
                                        ?>
                                        <div class="donors-member">
                                            <div class="mem-avata"><?php echo get_avatar($ord->getMember()->getField_value()[2]['value'], '70'); ?></div>
                                            <div class="mem-info">
                                                <div class="donate-info">
                                                    <div class="mem-name">
                                                        <?php echo ($ord->getMember()->getId() ? (($ord->getMember()->getField_value()[0]['name'] == 'full_name' && $ord->getMember()->getField_value()[0]['value']) ? $ord->getMember()->getField_value()[0]['value'] : __('No Name', 'inwavethemes')) : __('Anonymous', 'inwavethemes')); ?>
                                                    </div>
                                                    <div class="date">
                                                        <?php echo '<i class="fa fa-clock-o"></i>' . date('h A - l, F d,Y', $ord->getTime_paymented()); ?>
                                                    </div>
                                                </div>
                                                <span class="donate-value theme-bg">
                                                    <?php echo ($utility->getIWCurrencySymbol($ord->getCurrentcy()) . $ord->getSum_price()); ?>
                                                </span>

                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="sidebar-right related">
                        <h3 class="title iw-capital"><?php _e('Related Campaigns', 'inwavethemes'); ?></h3>
                        <?php echo do_shortcode('[infunding_list show_page_list="0" limit="1" order_by="rand" show_filter_bar="0" number_column="1"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

