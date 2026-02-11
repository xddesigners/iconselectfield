<?php
// moved to siteconfig extension
//namespace XD\IconSelectField\Admin;
//
//use SilverStripe\Admin\ModelAdmin;
//use SilverStripe\Forms\GridField\GridFieldConfig;
//use XD\IconSelectField\Models\IconGroup;
//
//class IconAdmin extends ModelAdmin
//{
//    private static $managed_models = [
//        IconGroup::class,
//    ];
//
//    private static $url_segment = 'icons';
//
//    private static $menu_title = 'Icons';
//
//    private static $menu_icon_class = 'font-icon-sun';
//
//
//    // make IconGroups sortable
//    protected function getGridFieldConfig(): GridFieldConfig
//    {
//        $config = parent::getGridFieldConfig();
//        $config->addComponent(new \Symbiote\GridFieldExtensions\GridFieldOrderableRows('Sort'));
//        return $config;
//    }
//
//
//}