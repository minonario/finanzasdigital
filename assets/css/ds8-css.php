<?php
//header('Content-type: text/css');

// Exit if accessed directly.
//defined( 'ABSPATH' ) || exit;

//require '/../../../wp-load.php'; // load wordpress bootstrap, this is what I don't like
//require('../../../../../wp-load.php');

// and from here on generate the css file and having access to the
// functions provided by wordpress

//$public_sss = $heateor_sss->plugin_public;
$public_css = $heateor_sss->options;

if ( isset( $public_css['plain_instagram_bg'] ) ) {
        ?>
        .heateor_sss_button_instagram span.heateor_sss_svg{background-color:#527fa4}
        <?php
} else {
        ?>
        .heateor_sss_button_instagram span.heateor_sss_svg,a.heateor_sss_instagram span.heateor_sss_svg{background:radial-gradient(circle at 30% 107%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285aeb 90%)}
        <?php
}
if ( $public_css['horizontal_bg_color_default'] != '' ) { ?>
        div.heateor_sss_horizontal_sharing a.heateor_sss_button_instagram span{background:<?php echo esc_html( $public_css['horizontal_bg_color_default'] ) ?>!important;}div.heateor_sss_standard_follow_icons_container a.heateor_sss_button_instagram span{background:<?php echo esc_html( $public_css['horizontal_bg_color_default'] ) ?>;}
<?php } ?>
<?php if ( $public_css['horizontal_bg_color_hover'] != '' ) { ?>
        div.heateor_sss_horizontal_sharing a.heateor_sss_button_instagram span:hover{background:<?php echo esc_html( $public_css['horizontal_bg_color_hover'] ) ?>!important;}div.heateor_sss_standard_follow_icons_container a.heateor_sss_button_instagram span:hover{background:<?php echo esc_html( $public_css['horizontal_bg_color_hover'] ) ?>;}
<?php } ?>
<?php if ( $public_css['vertical_bg_color_default'] != '' ) { ?>
        div.heateor_sss_vertical_sharing  a.heateor_sss_button_instagram span{background:<?php echo esc_html( $public_css['vertical_bg_color_default'] ) ?>!important;}div.heateor_sss_floating_follow_icons_container a.heateor_sss_button_instagram span{background:<?php echo esc_html( $public_css['vertical_bg_color_default'] ) ?>;}
<?php } ?>
<?php if ( $public_css['vertical_bg_color_hover'] != '' ) { ?>
        div.heateor_sss_vertical_sharing a.heateor_sss_button_instagram span:hover{background:<?php echo esc_html( $public_css['vertical_bg_color_hover'] ) ?>!important;}div.heateor_sss_floating_follow_icons_container a.heateor_sss_button_instagram span:hover{background:<?php echo esc_html( $public_css['vertical_bg_color_hover'] ) ?>;}
<?php } ?>
.heateor_sss_horizontal_sharing .heateor_sss_svg,.heateor_sss_standard_follow_icons_container .heateor_sss_svg{
        <?php if ( $public_css['horizontal_bg_color_default'] != '' ) { ?>
                background-color: <?php echo esc_html( $public_css['horizontal_bg_color_default'] ) ?>!important;
                background: <?php echo esc_html( $public_css['horizontal_bg_color_default'] ) ?>!important;
        <?php  } ?>
                color: <?php echo $public_css['horizontal_font_color_default'] ? esc_html( $public_css['horizontal_font_color_default'] ) : '#fff' ?>;
        <?php
        $border_width = 0;
        if ( $public_css['horizontal_border_width_default'] != '' ) {
                $border_width = $public_css['horizontal_border_width_default'];
        } elseif ( $public_css['horizontal_border_width_hover'] != '' ) {
                $border_width = $public_css['horizontal_border_width_hover'];
        }
        ?>
        border-width: <?php echo esc_html( $border_width ) . 'px' ?>;
        border-style: solid;
        border-color: <?php echo $public_css['horizontal_border_color_default'] != '' ? esc_html( $public_css['horizontal_border_color_default'] ) : 'transparent'; ?>;
}
<?php if ( $public_css['horizontal_font_color_default'] == '' ) {
        ?>
        .heateor_sss_horizontal_sharing .heateorSssTCBackground{
                color:#666;
        }
        <?php
}
if ( $public_css['horizontal_font_color_hover'] != '' ) { ?>
        div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover path:not(.heateor_sss_no_fill),div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover ellipse, div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover circle, div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover polygon, div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover rect:not(.heateor_sss_no_fill){
        fill: <?php echo esc_html( $public_css['horizontal_font_color_hover'] ) ?>;
    }
    div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover path.heateor_sss_svg_stroke, div.heateor_sss_horizontal_sharing span.heateor_sss_svg svg:hover rect.heateor_sss_svg_stroke{
        stroke: <?php echo esc_html( $public_css['horizontal_font_color_hover'] ) ?>;
    }
<?php } ?>
.heateor_sss_horizontal_sharing span.heateor_sss_svg:hover,.heateor_sss_standard_follow_icons_container span.heateor_sss_svg:hover{
        <?php if ( $public_css['horizontal_bg_color_hover'] != '' ) { ?>
                background-color: <?php echo esc_html( $public_css['horizontal_bg_color_hover'] ) ?>!important;
                background: <?php echo esc_html( $public_css['horizontal_bg_color_hover'] ) ?>!important;
        <?php }
        if ( $public_css['horizontal_font_color_hover'] != '' ) { ?>
                color: <?php echo esc_html( $public_css['horizontal_font_color_hover'] ) ?>;
        <?php  } ?>
        border-color: <?php echo $public_css['horizontal_border_color_hover'] != '' ? esc_html( $public_css['horizontal_border_color_hover'] ) : 'transparent'; ?>;
}
.heateor_sss_vertical_sharing span.heateor_sss_svg,.heateor_sss_floating_follow_icons_container span.heateor_sss_svg{
        <?php if ( $public_css['vertical_bg_color_default'] != '' ) { ?>
                background-color: <?php echo esc_html( $public_css['vertical_bg_color_default'] ) ?>!important;
                background: <?php echo esc_html( $public_css['vertical_bg_color_default'] ) ?>!important;
        <?php } ?>
                color: <?php echo $public_css['vertical_font_color_default'] ? esc_html( $public_css['vertical_font_color_default'] ) : '#fff' ?>;
        <?php
        $vertical_border_width = 0;
        if ( $public_css['vertical_border_width_default'] != '' ) {
                $vertical_border_width = $public_css['vertical_border_width_default'];
        } elseif ( $public_css['vertical_border_width_hover'] != '' ) {
                $vertical_border_width = $public_css['vertical_border_width_hover'];
        }
        ?>
        border-width: <?php echo esc_html( $vertical_border_width ) ?>px;
        border-style: solid;
        border-color: <?php echo $public_css['vertical_border_color_default'] != '' ? esc_html( $public_css['vertical_border_color_default'] ) : 'transparent'; ?>;
}
<?php if ( $public_css['horizontal_font_color_default'] == '' ) { ?>
.heateor_sss_vertical_sharing .heateorSssTCBackground{
        color:#666;
}
<?php } ?>
<?php if ( $public_css['vertical_font_color_hover'] != '' ) { ?>
    div.heateor_sss_vertical_sharing span.heateor_sss_svg svg:hover path:not(.heateor_sss_no_fill),div.heateor_sss_vertical_sharing span.heateor_sss_svg svg:hover ellipse, div.heateor_sss_vertical_sharing span.heateor_sss_svg svg:hover circle, div.heateor_sss_vertical_sharing span.heateor_sss_svg svg:hover polygon{
        fill:<?php echo esc_html( $public_css['vertical_font_color_hover'] ) ?>;
    }
    div.heateor_sss_vertical_sharing span.heateor_sss_svg svg:hover path.heateor_sss_svg_stroke{
        stroke:<?php echo esc_html( $public_css['vertical_font_color_hover'] ) ?>;
    }
<?php } ?>
.heateor_sss_vertical_sharing span.heateor_sss_svg:hover,.heateor_sss_floating_follow_icons_container span.heateor_sss_svg:hover{
        <?php if ( $public_css['vertical_bg_color_hover'] != '' ) { ?>
                background-color: <?php echo esc_html( $public_css['vertical_bg_color_hover'] ) ?>!important;
                background: <?php echo esc_html( $public_css['vertical_bg_color_hover'] ) ?>!important;
        <?php }
        if ( $public_css['vertical_font_color_hover'] != '' ) { ?>
                color: <?php echo esc_html( $public_css['vertical_font_color_hover'] ) ?>;
        <?php  } ?>
        border-color: <?php echo $public_css['vertical_border_color_hover'] != '' ? esc_html( $public_css['vertical_border_color_hover'] ) : 'transparent'; ?>;
}
<?php
if ( isset( $public_css['horizontal_counts'] ) ) {
        $svg_height = $public_css['horizontal_sharing_shape'] == 'rectangle' ? $public_css['horizontal_sharing_height'] : $public_css['horizontal_sharing_size'];
        if ( isset( $public_css['horizontal_counter_position'] ) && in_array( $public_css['horizontal_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ) {
                $line_height_percent = $public_css['horizontal_counter_position'] == 'inner_top' ? 38 : 19;
                ?>
                div.heateor_sss_horizontal_sharing svg{height:70%;margin-top:<?php echo esc_html( $svg_height )*15/100 ?>px}div.heateor_sss_horizontal_sharing .heateor_sss_square_count{line-height:<?php echo esc_html( $svg_height*$line_height_percent )/100 ?>px;}
                <?php
        } elseif ( isset( $public_css['horizontal_counter_position'] ) && in_array( $public_css['horizontal_counter_position'], array( 'inner_left', 'inner_right' ) ) ) { ?>
                div.heateor_sss_horizontal_sharing svg{width:50%;margin:auto;}div.heateor_sss_horizontal_sharing .heateor_sss_square_count{float:left;width:50%;line-height:<?php echo esc_html( $svg_height - 2 * $border_width ); ?>px;}
                <?php
        } elseif ( isset( $public_css['horizontal_counter_position'] ) && in_array( $public_css['horizontal_counter_position'], array( 'left', 'right' ) ) ) { ?>
                div.heateor_sss_horizontal_sharing .heateor_sss_square_count{float:<?php echo esc_html( $public_css['horizontal_counter_position'] ) ?>;margin:0 8px;line-height:<?php echo esc_html( $svg_height ); ?>px;}
                <?php
        } elseif ( ! isset( $public_css['horizontal_counter_position'] ) || $public_css['horizontal_counter_position'] == 'top' ) { ?>
                div.heateor_sss_horizontal_sharing .heateor_sss_square_count{display: block}
                <?php
        }
}
if ( isset( $public_css['vertical_counts'] ) ) {
        $vertical_svg_height = $public_css['vertical_sharing_shape'] == 'rectangle' ? $public_css['vertical_sharing_height'] : $public_css['vertical_sharing_size'];
        $vertical_svg_width = $public_css['vertical_sharing_shape'] == 'rectangle' ? $public_css['vertical_sharing_width'] : $public_css['vertical_sharing_size'];
        if ( ( isset( $public_css['vertical_counter_position'] ) && in_array( $public_css['vertical_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ) || ! isset( $public_css['vertical_counter_position'] ) ) {
                $vertical_line_height_percent = ! isset( $public_css['vertical_counter_position'] ) || $public_css['vertical_counter_position'] == 'inner_top' ? 38 : 19;
                ?>
                div.heateor_sss_vertical_sharing svg{height:70%;margin-top:<?php echo esc_html( $vertical_svg_height )*15/100 ?>px}div.heateor_sss_vertical_sharing .heateor_sss_square_count{line-height:<?php echo esc_html( $vertical_svg_height*$vertical_line_height_percent )/100; ?>px;}
                <?php
        } elseif ( isset( $public_css['vertical_counter_position'] ) && in_array( $public_css['vertical_counter_position'], array( 'inner_left', 'inner_right' ) ) ) { ?>
                div.heateor_sss_vertical_sharing svg{width:50%;margin:auto;}div.heateor_sss_vertical_sharing .heateor_sss_square_count{float:left;width:50%;line-height:<?php echo esc_html( $vertical_svg_height ); ?>px;}
                <?php
        }  elseif ( isset( $public_css['vertical_counter_position'] ) && in_array( $public_css['vertical_counter_position'], array( 'left', 'right' ) ) ) { ?>
                div.heateor_sss_vertical_sharing .heateor_sss_square_count{float:<?php echo esc_html( $public_css['vertical_counter_position'] ) ?>;margin:0 8px;line-height:<?php echo esc_html( $vertical_svg_height ); ?>px; <?php echo $public_css['vertical_counter_position'] == 'left' ? 'min-width:' . esc_html( $vertical_svg_width )*30/100 . 'px;display: block' : '';?>}
                <?php
        } elseif ( isset( $public_css['vertical_counter_position'] ) && $public_css['vertical_counter_position'] == 'top' ) { ?>
                div.heateor_sss_vertical_sharing .heateor_sss_square_count{display:block}
                <?php
        }
}
echo isset( $public_css['hide_mobile_sharing'] ) && $public_css['vertical_screen_width'] != '' ? '@media screen and (max-width:' . intval( $public_css['vertical_screen_width'] ) . 'px) {.heateor_sss_vertical_sharing{display:none!important}}' : '';

$bottom_sharing_postion_inverse = $public_css['bottom_sharing_alignment'] == 'left' ? 'right' : 'left';
$bottom_sharing_responsive_css = '';
if ( isset( $public_css['vertical_enable'] ) && $public_css['bottom_sharing_position_radio'] == 'responsive' ) {
        $vertical_sharing_icon_height = $public_css['vertical_sharing_shape'] == 'rectangle' ? $public_css['vertical_sharing_height'] : $public_css['vertical_sharing_size'];
        $num_sharing_icons = isset($public_css['vertical_re_providers']) ? count($public_css['vertical_re_providers']) : 0;
        $total_share_count_enabled = isset($public_css['vertical_total_shares']) ? 1 : 0;
        $more_icon_enabled = isset($public_css['vertical_more']) ? 1 : 0;
        $bottom_sharing_responsive_css = 'div.heateor_sss_bottom_sharing{width:100%!important;left:0!important;}div.heateor_sss_bottom_sharing a{width:'.(100/($num_sharing_icons+$total_share_count_enabled+$more_icon_enabled)).'% !important;}div.heateor_sss_bottom_sharing .heateor_sss_svg{width: 100% !important;}div.heateor_sss_bottom_sharing div.heateorSssTotalShareCount{font-size:1em!important;line-height:' . ( $vertical_sharing_icon_height*70/100 ) . 'px!important}div.heateor_sss_bottom_sharing div.heateorSssTotalShareText{font-size:.7em!important;line-height:0px!important}';
}
echo isset( $public_css['vertical_enable'] ) && isset( $public_css['bottom_mobile_sharing'] ) && $public_css['horizontal_screen_width'] != '' ? 'div.heateor_sss_mobile_footer{display:none;}@media screen and (max-width:' . intval( $public_css['horizontal_screen_width'] ) . 'px){div.heateor_sss_bottom_sharing .heateorSssTCBackground{background-color:white}' . $bottom_sharing_responsive_css . 'div.heateor_sss_mobile_footer{display:block;height:' . esc_html( $public_css['vertical_sharing_shape'] == 'rectangle' ? $public_css['vertical_sharing_height'] : $public_css['vertical_sharing_size'] ) . 'px;}.heateor_sss_bottom_sharing{padding:0!important;' . esc_html( $public_css['bottom_sharing_position_radio'] == 'nonresponsive' && $public_css['bottom_sharing_position'] != '' ? esc_html( $public_css['bottom_sharing_alignment'] ) . ':' . esc_html( $public_css['bottom_sharing_position'] ) . 'px!important;' . esc_html( $bottom_sharing_postion_inverse ) . ':auto!important;' : '' ) . 'display:block!important;width:auto!important;bottom:' . ( isset( $public_css['vertical_total_shares'] ) && ! $public_sss->is_amp_page() ? '-5' : '-2' ) . 'px!important;top: auto!important;}.heateor_sss_bottom_sharing .heateor_sss_square_count{line-height:inherit;}.heateor_sss_bottom_sharing .heateorSssSharingArrow{display:none;}.heateor_sss_bottom_sharing .heateorSssTCBackground{margin-right:1.1em!important}}' : '';
echo esc_html( $public_css['custom_css'] );
echo isset( $public_css['hide_slider'] ) ? 'div.heateorSssSharingArrow{display:none}' : '';
if ( isset( $public_css['hor_enable'] ) && $public_css['hor_sharing_alignment'] == "center" ) {
        echo 'div.heateor_sss_sharing_title{text-align:center}div.heateor_sss_sharing_ul{width:100%;text-align:center;}div.heateor_sss_horizontal_sharing div.heateor_sss_sharing_ul a{float:none!important;display:inline-block;}';
}
?>