<?php

namespace XD\IconSelectField\Forms;

use InvalidArgumentException;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\GroupedDropdownField;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\View\Parsers\HTMLValue;
use SilverStripe\View\Requirements;
use SilverStripe\View\TemplateGlobalProvider;
use XD\IconSelectField\Fields\DBIcon;
use XD\IconSelectField\Models\IconGroup;
use XD\IconSelectField\Models\Icon;

class IconSelectField extends GroupedDropdownField implements TemplateGlobalProvider
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

    public function Field($properties = [])
    {
        Requirements::css('xddesigners/iconselectfield:css/IconSelectField.css');
        Requirements::javascript('xddesigners/iconselectfield:js/IconSelectField.js');

        $source = $this->getSource();

        $odd = 0;
        $fieldExtraClass = $this->extraClass();
        $groups = [];

        if ($source) {
            $i = 0;
            foreach ($source as $name => $values) {

                if (is_array($values)) {
                    $options = [];
                    if ($i == 0) {
                        $options[] = new ArrayData([
                            'ID' => 0,
                            'Name' => $this->name,
                            'Value' => '',
                            'Title' => '',
                            'isChecked' => '' == $this->value,
                            'isDisabled' => $this->disabled || in_array('', $this->disabledItems),
                        ]);
                    }

                    foreach ($values as $value => $icon) {
                        $itemID = $this->ID() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $value);
                        $options[] = new ArrayData([
                            'ID' => $itemID,
                            'Name' => $this->name,
                            'Value' => $value,
                            'Title' => $icon,
                            'isChecked' => $value == $this->value,
                            'isDisabled' => $this->disabled || in_array($value, $this->disabledItems),
                        ]);
                    }

                    $groups[] = new ArrayData(
                        [
                            'ID' => $this->ID() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $name),
                            'extraClass' => $fieldExtraClass,
                            'Name' => $name,
                            'Options' => new ArrayList($options),
                        ]
                    );
                    $i++;
                } else {
                    throw new InvalidArgumentException('To use IconSelectField you need to pass in an array of array\'s');
                }
            }
        }

        $properties = array_merge(
            $properties,
            [
                'Groups' => new ArrayList($groups),
            ]
        );

        return $this->customise($properties)->renderWith(
            $this->getTemplates()
        );
    }

    public function getIcons()
    {

        $groups = [];

        $iconGroups = IconGroup::get();
        if (!$iconGroups->exists()) {
            return [];
        }

        foreach ($iconGroups as $iconGroup) {
            $icons = [];
            $iconList = $iconGroup->Icons();
            foreach ($iconList as $icon) {
                $icons[$icon->Title] = $icon->getPreview();
            }
            $groups[$iconGroup->Title] = $icons;
        }

        return $groups;
    }

    /**
     * @return string
     */
    public function Type()
    {
        return 'icon-select-field';
    }

    public static function IconSelectIcon($iconName)
    {
        return DBIcon::create()->setValue($iconName);
    }

    public static function get_template_global_variables()
    {
        return [
            'IconSelectIcon' => 'IconSelectIcon',
        ];
    }
}
