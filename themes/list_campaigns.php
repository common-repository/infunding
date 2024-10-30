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
 * Description of list_campaigns
 *
 * @developer duongca
 */
$utility = new inFundingUtility();
$query = $utility->getCampaignsList($category, $ids, $order_by, $order_dir, $limit, false, $page);
$layout = filter_input(INPUT_GET, 'layout');
if ($layout) {
    $style = ($layout == 'grid' ? 'style1' : 'style2');
}
?>
<div class="infunding-listing-page <?php echo esc_attr($class); ?>">
    <?php if ($show_filter_bar): ?>
        <div class="filter-item">
            <div class="filter-form">
                <?php $utility->getInfundingFilterForm($category); ?>
            </div>
            <div class="iw-filter-style">
                <div class="list-view<?php echo ($style == 'style2') ? ' active theme-bg' : ' inactive'; ?>">
                    <?php if ($style == 'style1'): ?>
                        <a href="?layout=list" class="theme-clor"><i class="fa fa-th-list"></i></a>
                    <?php else: ?>
                        <i class="fa fa-th-list"></i>
                    <?php endif; ?>
                </div>
                <div class="grid-view<?php echo ($style == 'style1') ? ' active theme-bg' : ' inactive'; ?>">
                    <?php if ($style == 'style2'): ?>
                        <a href="?layout=grid" class="theme-clor"><i class="fa fa-th"></i></a>
                    <?php else: ?>
                        <i class="fa fa-th"></i>
                    <?php endif; ?>
                </div>
            </div>
            <div style="clear: both"></div>
        </div>
    <?php endif; ?>
    <section class="campaing-listing infunding_<?php echo esc_attr($style) ?>">
        <?php
        if ($query->have_posts()) {
            $path = inFundingGetTemplatePath('infunding/list_campaigns-' . esc_attr($style));
            if ($path) {
                include $path;
            } else {
                $inf_theme = INFUNDING_THEME_PATH . 'list_campaigns-' . esc_attr($style) . '.php';
                if (file_exists($inf_theme)) {
                    include $inf_theme;
                } else {
                    echo __('No theme was found', 'inwavethemes');
                }
            }
        } else {
            echo __('No campaign found', 'inwavethemes');
        }
        ?>
        <!--        <div style="clear: both;"></div>-->
    </section>
    <?php if ($show_page_list): ?>
        <div class="load-campaigns">
            <?php
            $rs = $utility->infunding_display_pagination($query);
            if ($rs['success']) {
                echo esc_html($rs['data']);
            }
            ?>
        </div>
    <?php endif; ?>
</div>

