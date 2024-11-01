<?php

/*
  Plugin Name: WPML Flag in Menu Extended
  Plugin URI: http://www.enovision.nl/WPML_Flag_In_Menu_Extended
  Description: Based on the WPML flag in menu plugin from Ramon Fincken. With menu selection option.
  Version: 1.7
  Author: Johan van de Merwe
  Author URI: http://www.enovision.net
 */

include(dirname(__FILE__) . '/settings/settings.php');

class WPML_Flags_extended
{

    const PLUGIN_NAME = 'wpml-flag-in-menu-extended';
    const PLUGIN_TITLE = 'WPML Flags in Menu Extended';
    const PLUGIN_VERSION = '1.6';
    const DEFAULT_LANG = 'en';

    var $success = false;

    function __construct()
    {

        add_action('init', array($this, 'SetLanguage'), 1);

        add_action('admin_menu', array($this, 'InsertAdminMenuLink'));

        add_action('admin_head', array($this, 'AdminStyles'));

        add_action('admin_init', array($this, 'ConfigSettings')); // Options Page Init

        add_action('wp_print_styles', array($this, 'Styles'));

        if (is_admin() == false) {
            add_filter('wp_nav_menu_items', array($this, 'wpml_flag_in_menu_extended'), 10, 2);
        }

        $this->errors = new WP_Error();
    }

    function SetLanguage()
    {
        load_plugin_textdomain(self::PLUGIN_NAME, null, dirname(__FILE__) . '/languages');
    }

    function InsertAdminMenuLink()
    {
        $options_page = add_options_page(
            __(self::PLUGIN_TITLE, self::PLUGIN_NAME), __(self::PLUGIN_TITLE, self::PLUGIN_NAME), 'manage_options', self::PLUGIN_NAME, array($this, 'ConfigPageHtml'
        ));
    }

    function ConfigSettings()
    {
        $this->settings_api = WPML_Flags_extended_Settings::getInstance(array(
            'name' => self::PLUGIN_NAME,
            'title' => __('Plugin options', self::PLUGIN_NAME) . ' ' . self::PLUGIN_NAME
        ));

        $this->settings_api->admin_init();
    }

    function ConfigPageHtml()
    {

        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    function AdminStyles()
    {
        wp_register_style('wpml-flags-menu-admin-styles', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/css/admin.css');
        wp_enqueue_style('wpml-flags-menu-admin-styles');
    }

    function InsertSettingsLink($links)
    {
        $settings_link = '<a href="options-general.php?page=' . self::PLUGIN_NAME . '">' . __('Settings', 'WPML_Flags') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    function Styles()
    {
        wp_register_style('wpml-flags-menu-styles', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . '/css/style.css');
        wp_enqueue_style('wpml-flags-menu-styles');
    }

    function wpml_flag_in_menu_extended($items, $args)
    {

        $this->settings_api = WPML_Flags_extended_Settings::getInstance();

        // General Settings
        $menu = $this->settings_api->get_option('menu', 'wpml-flag-in-menu-extended_basics', false);
        $desc_too = $this->settings_api->get_option('show-description', 'wpml-flag-in-menu-extended_basics', false);
        $translation = $this->settings_api->get_option('description-translation', 'wpml-flag-in-menu-extended_basics', 'N');
        $desc_tag = $this->settings_api->get_option('description-tag', 'wpml-flag-in-menu-extended_basics', 'span');
        $chk_active_language = $this->settings_api->get_option('chk-active-language', 'wpml-flag-in-menu-extended_basics', 'off');
        // Styling
        $img_class = $this->settings_api->get_option('img_class', 'wpml-flag-in-menu-extended_styling', '');
        $img_width = $this->settings_api->get_option('img_width', 'wpml-flag-in-menu-extended_styling', '');
        $img_height = $this->settings_api->get_option('img_height', 'wpml-flag-in-menu-extended_styling', '');
        $li_class = $this->settings_api->get_option('li_class', 'wpml-flag-in-menu-extended_styling', '');

        // added in version 1.7
        $flag_wrap = $this->settings_api->get_option('chk-flag-wrap', 'wpml-flag-in-menu-extended_styling', 'off');
        $flag_wrap_tag = $this->settings_api->get_option('flag_wrap_tag', 'wpml-flag-in-menu-extended_styling', 'div');
        $flag_wrap_class = $this->settings_api->get_option('flag_wrap_class', 'wpml-flag-in-menu-extended_styling', '');

        /* solved in version 1.4 */
        if (!isset($args->menu->slug)) {
            // then the menu is a string value
            $mo = wp_get_nav_menu_object($args->menu);
            $this_menu = $mo->slug;
        } else {
            $this_menu = $args->menu->slug;
        }

        $i_class = $img_class != '' ? ' class="' . $img_class . '"' : '';
        $d_class = $img_class != '' ? ' class="' . $img_class . '_name"' : '';
        $li_class = $li_class != '' ? $li_class : '';

        $new_items = '';
        $flag_menu_only = false;

        if (function_exists('icl_get_languages') && isset($menu[$this_menu])) {
            $languages = icl_get_languages('skip_missing=0&orderby=code');

            $menu_items = wp_get_nav_menu_items($this_menu, $args);

            // check for wpml_flag_menu option, if it exists it will be replaced
            // by only the flag menu, any other option will be overwritten
            foreach ($menu_items as $item) {
                if ($item->post_title == '[wpml_flag_menu]' || $item->post_name == 'wpml_flag_menu') {
                    $flag_menu_only = true;
                }
            }

            if (!empty($languages)) {
                $total = count($languages);
                $count = 1;
                $last_l = end($languages);

                $wrap_start = false;

                foreach ($languages as $idx => $l) {
                    $last = ' not-last ';

                    // Exclude current viewing language
                    if ($l['language_code'] != ICL_LANGUAGE_CODE || $chk_active_language === 'on') {

                        if ($count == $total) {
                            $last = ' last ';
                        } else {
                            if ($count + 1 == $total && $last_l['language_code'] == ICL_LANGUAGE_CODE) {
                                $last = ' last ';
                            }
                        }

                        if (!$wrap_start && $flag_wrap === 'on') {
                            $new_items .= sprintf('<%s %s>',
                                $flag_wrap_tag,
                                $flag_wrap_class !== '' ? 'class="' . $flag_wrap_class . '"' : ''
                            );
                            $wrap_start = true;
                        }

                        $new_items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page ' . $li_class . $last . '">';
                        if (!$l['active'])
                            $new_items .= '<a href="' . $l['url'] . '">';
                        if ($l['country_flag_url']) {
                            $height = ($img_height != '') ? ' " height="' . $img_height . '" ' : '';
                            $width = ($img_width != '') ? ' " height="' . $img_width . '" ' : '';
                            $new_items .= '<img ' . $i_class . ' src="' . $l['country_flag_url'] . $height . $width . '" alt="' . $l['language_code'] . '" width="' . $img_width . '" />';
                            if ($desc_too[$this_menu]) {
                                $t = $translation == 'T' ? $l['translated_name'] : $l['native_name'];
                                $new_items .= '<' . $desc_tag . ' ' . $d_class . '>' . $t . '</' . $desc_tag . '>';
                            }
                        }

                        if (!$l['active'])
                            $new_items .= '</a>';
                        $new_items .= '</li>';

                    }

                    $count++;
                }

                if ($total > 0 && $flag_wrap === 'on') {
                    $new_items .= sprintf('</%s>',
                        $flag_wrap_tag
                    );
                }
            }

            // Idea by Simon Weil
            if (is_rtl()) {
                $items = $new_items . ($flag_menu_only == false ? $items : '');
            } else {
                $items = ($flag_menu_only == false ? $items : '') . $new_items;
            }

        }

        return $items;

    }

}

$WPML_Flags_extended = new WPML_Flags_extended();