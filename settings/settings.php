<?php

include(dirname(__FILE__) . '/class.settings-api.php');

class WPML_Flags_extended_Settings extends WPML_Flags_extended_Settings_API
{

    const PLUGIN_NAME = 'wpml-flag-in-menu-extended';

    private static $_instance;
    public $name, $Plugin;

    function __construct($args = array())
    {

        load_plugin_textdomain(self::PLUGIN_NAME, null, dirname(__FILE__) . '/languages');

        $this->name = isset($args['name']) ? $args['name'] : self::PLUGIN_NAME;
        $this->PageTitle = isset($args['title']) ? $args['title'] : __('Plugin Options', self::PLUGIN_NAME);

        $this->SetPluginName($this->name); // to make sure something is in it

        $this->ConfigSections();
        $this->ConfigFields();
    }

    public static function getInstance($args = array())
    {
        if (!self::$_instance) {
            self::$_instance = new WPML_Flags_extended_Settings($args);
        }

        return self::$_instance;
    }

    public function SetPluginName($name = 'WPML_Flags')
    {
        $this->Plugin = $name;
    }

    public function SetPageTitle($title = 'Plugin Options')
    {
        $this->title = $title;
    }

    private function ConfigSections()
    {
        $pi = $this->Plugin;
        $sections = array(
            array(
                'id' => $pi . '_basics',
                'title' => __('General Settings', self::PLUGIN_NAME)
            ),
            array(
                'id' => $pi . '_styling',
                'title' => __('Styling', self::PLUGIN_NAME)
            )
        );

        $this->set_sections($sections);
        return;
    }

    /* Fields */

    private function ConfigFields()
    {
        $pi = $this->Plugin;

        $menu = $this->LoadMenu();

        $fields = array(
            $pi . '_basics' => array(
                array(
                    'name' => 'menu',
                    'label' => __('Show language flag on these menus', self::PLUGIN_NAME),
                    'desc' => __('Select menus where the flags are added', self::PLUGIN_NAME),
                    'type' => 'multicheck',
                    'options' => $menu
                ),
                array(
                    'name' => 'show-description',
                    'label' => __('Include also the description after the flag on these menus', self::PLUGIN_NAME),
                    'desc' => __('Select menus to add the description after the flag', self::PLUGIN_NAME),
                    'type' => 'multicheck',
                    'options' => $menu
                ),
                array(
                    'name' => 'description-translation',
                    'label' => __('Show description', self::PLUGIN_NAME),
                    'desc' => __('In what language should the description be shown', self::PLUGIN_NAME),
                    'type' => 'radio',
                    'default' => 'N',
                    'options' => array(
                        'N' => __('Native (like English in English)', self::PLUGIN_NAME),
                        'T' => __('Translated (like Engels for English on a Dutch page)', self::PLUGIN_NAME)
                    )
                ),
                array(
                    'name' => 'chk-active-language',
                    'label' => __('Also show active language', self::PLUGIN_NAME),
                    'desc' => __('When checked it will also show the active language', self::PLUGIN_NAME),
                    'type' => 'checkbox',
                    'default' => false
                )
            ),
            $pi . '_styling' => array(
                array(
                    'name' => 'li_class',
                    'label' => __('List(li) CSS class', self::PLUGIN_NAME),
                    'desc' => __('CSS class for the LI tag', self::PLUGIN_NAME),
                    'type' => 'text',
                    'default' => 'wpml_flags'
                ),
                array(
                    'name' => 'img_class',
                    'label' => __('Image CSS class', self::PLUGIN_NAME),
                    'desc' => __('CSS class for the IMG tag', self::PLUGIN_NAME),
                    'type' => 'text',
                    'default' => 'wpml_flags'
                ),
                array(
                    'name' => 'img_height',
                    'label' => __('Image height', self::PLUGIN_NAME),
                    'desc' => __('default: 16, empty leaves the height tag out', self::PLUGIN_NAME),
                    'type' => 'text',
                    'default' => '16'
                ),
                array(
                    'name' => 'img_width',
                    'label' => __('Image width', self::PLUGIN_NAME),
                    'desc' => __('default: 16, empty leaves the width tag out', self::PLUGIN_NAME),
                    'type' => 'text',
                    'default' => '16'
                ),
                array(
                    'name' => 'description-tag',
                    'label' => __('HTML tag surrounding the language description', self::PLUGIN_NAME),
                    'desc' => __('The HTML tag surrounding the language description (if used), it will automatically have the class "wpml_flags_name" as entered in the CSS class for the IMG tag', self::PLUGIN_NAME),
                    'type' => 'radio',
                    'default' => 'span',
                    'options' => array(
                        'span' => __('&lt;span&gt;English&lt;/span&gt;', self::PLUGIN_NAME),
                        'div' => __('&lt;div&gt;English&lt;/div&gt;', self::PLUGIN_NAME)
                    )
                ),
                array(
                    'name' => 'chk-flag-wrap',
                    'label' => __('Add flags wrapper around the flags li elements?', self::PLUGIN_NAME),
                    'desc' => __('Only check this, if you want a wrapper around the flags li elements, for example to right justify the flags on a menu', self::PLUGIN_NAME),
                    'type' => 'checkbox',
                    'default' => false
                ),
                array(
                    'name' => 'flag_wrap_tag',
                    'label' => __('Flags wrapper html tag', self::PLUGIN_NAME),
                    'desc' => __('HTML tag to wrap it in, for example DIV or SPAN', self::PLUGIN_NAME),
                    'type' => 'radio',
                    'default' => 'div',
                    'options' => array(
                        'span' => __('span', self::PLUGIN_NAME),
                        'div' => __('div', self::PLUGIN_NAME)
                    )
                ),
                array(
                    'name' => 'flag_wrap_class',
                    'label' => __('Flags wrapper additional classes', self::PLUGIN_NAME),
                    'desc' => __('Extra classes to add to the flags wrapper', self::PLUGIN_NAME),
                    'type' => 'text',
                    'default' => ''
                )
            )
        );

        $this->set_fields($fields);
        return;
    }

    private function LoadMenu()
    {

        $menus = get_terms('nav_menu', array('hide_empty' => false));

        $menu = array();
        foreach ((array)$menus as $key => $menu_item) {
            $menu[$menu_item->slug] = $menu_item->name;
        }

        return $menu;
    }

}

?>
