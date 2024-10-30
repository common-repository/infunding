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
$options = array(
    'cat' => $category,
    'ids' => $ids,
    'show_des' => $show_des ? true : false,
    'show_location' => $show_des ? true : false,
    'desc_text_limit' => $desc_text_limit,
    'map'=>str_replace('``', '"', $map)
);
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        <?php $utility->inFundingRenderMap($options); ?>
    });
</script>
<div class="infunding-map infuding-map-container">
    <div class="infuding-map-wrap">
        <div class="map-preview" style="height:450px;">
        </div>
    </div>
</div>

