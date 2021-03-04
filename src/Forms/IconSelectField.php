<?php

namespace XD\IconSelectField\Forms;

use Heyday\ColorPalette\Fields\GroupedColorPaletteField;
use SilverStripe\View\Requirements;
use SilverStripe\View\TemplateGlobalProvider;
use XD\IconSelectField\Fields\DBIcon;

class IconSelectField extends GroupedColorPaletteField implements TemplateGlobalProvider
{
    /**
     * Configure icons, can be configured in groups
     * ```
     * XD\IconSelectField\IconSelectField
     *  icons:
     *    group:
     *       iconLabel: 'iconValue'
     *       iconLabel: 'iconValue'
     * ```
     * @var array
     */
    private static $icons = [];

    /**
     * Include your fonts script
     * These are injected with insertHeadTags so you can define js or css includes here
     *
     * @var array
     */
    private static $fonts_include = [
        /*'<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">'*/
    ];

    /**
     * Can be empty by default
     *
     * @var bool
     */
    protected $hasEmptyDefault = true;

    public function __construct($name, $title = null)
    {
        $icons = $this->getIcons();
        parent::__construct($name, $title, $icons, $value = null);
    }

    public function getIcons()
    {
        $groups = [];
        $group = [];
        $icons = self::config()->get('icons');
        foreach ($icons as $label => $icon) {
            if (is_array($icon)) {
                $groups[$label] = $icon;
            } else {
                $group[$label] = $icon;
            }
        }
        if (!empty($group)) {
            $groups['Icons'] = $group;
        }

        return $groups;
    }

    /**
     * @return string
     */
    public function Type()
    {
        return 'icon-select-field groupedcolorpalette colorpalette';
    }

    public function Field($properties = [])
    {
        // todo vendor requirement
        Requirements::css('xddesigners/iconselectfield:css/IconSelectField.css');
        return parent::Field($properties);
    }

    public static function IconSelectIcon($iconName)
    {
        return DBIcon::create()->setValue($iconName);
    }

    public static function get_template_global_variables()
    {
        return [
            'IconSelectIcon' => 'IconSelectIcon'
        ];
    }
}
