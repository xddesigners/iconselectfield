<?php

namespace XD\IconSelectField\Models;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use XD\IconSelectField\Forms\IconSelectField;

class IconGroup extends DataObject
{
    private static $table_name = 'IconSelectField_IconGroup';


    private static $icons = [];

    private static $icon_classes = [];

    private static $db = [
        'Title' => 'Varchar',
        'Sort' => 'Int',
        'IconClass' => 'Varchar',
        'IconStyle' => 'Varchar',
    ];

    private static $has_many = [
        'Icons' => Icon::class,
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'IconClassNice' => 'Icon Class',
        'IconStyleNice' => 'Icon Style',
        'Icons.Count' => 'Icons',
    ];

    private static $default_sort = 'Sort ASC';

    public function getCMSFields()
    {
//        $this->requireDefaultRecords();

        $fields = parent::getCMSFields();

        $fields->removeByName(['Sort', 'Icons', 'IconClass']);

        // create dropdown for icon class
        $iconClasses = IconSelectField::config()->get('icon_classes');
        $fields->addFieldToTab('Root.Main',
            DropdownField::create('IconClass', _t(__CLASS__ . '.IconClass', 'Icon class'), $iconClasses)
//                ->setEmptyString(_t(__CLASS__ . '.SelectIconClass', '-'))
        );

        // create text field for icon style
        $iconStyles = IconSelectField::config()->get('icon_styles');
        $fields->addFieldToTab('Root.Main',
            DropdownField::create('IconStyle', _t(__CLASS__ . '.IconStyle', 'Icon style'), $iconStyles)
                ->setEmptyString(_t(__CLASS__ . '.SelectIconStyle', '-'))
        );


        // add Icons field to Root.Main tab
        $config = GridFieldConfig_RecordEditor::create();
        $config->addComponent(new GridFieldOrderableRows('Sort'));

        $field = GridField::create('Icons', _t(__CLASS__ . '.Icons', 'Icons'), $this->Icons(), $config);
        $fields->addFieldToTab('Root.Main', $field);

        return $fields;
    }

    public function getIconClassNice()
    {
        $iconClasses = IconSelectField::config()->get('icon_classes');
        return $iconClasses[$this->IconClass] ?? $this->IconClass;
    }

    public function getIconStyleNice()
    {
        $iconStyles = IconSelectField::config()->get('icon_styles');
        return $iconStyles[$this->IconStyle] ?? $this->IconStyle;
    }


    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        // only create default icons if none exist
        if (IconGroup::get()->count() > 0) {
            return;
        }

        $icons = IconSelectField::config()->get('icons');
        if (!empty($icons)) {
            $groupSort = 0;
            foreach ($icons as $groupTitle => $group) {

                $iconClass = $group['icon_class'] ?? '';
                $iconStyle = $group['icon_style'] ?? '';
                $iconList = $group['icons'] ?? [];

                $iconGroup = IconGroup::get()->filter('Title', $groupTitle)->first();
                if (!$iconGroup) {
                    $iconGroup = IconGroup::create();
                    $iconGroup->Title = $groupTitle;
                    $iconGroup->Sort = $groupSort++;
                    $iconGroup->IconClass = $iconClass;
                    $iconGroup->IconStyle = $iconStyle;
                    $iconGroup->write();
                    echo "Created Icon Group: " . $groupTitle . "\n";
                }

                $iconSort = 0;
                foreach ($iconList as $iconTitle => $iconValue) {
                    if (!$iconTitle) continue;
                    $icon = Icon::get()->filter(['Title' => $iconTitle, 'IconGroupID' => $iconGroup->ID])->first();
                    if (!$icon) {
                        $icon = Icon::create();
                        $icon->Title = $iconTitle;
                        $icon->Value = $iconValue;
                        $icon->IconGroupID = $iconGroup->ID;
                        $icon->Sort = $iconSort++;
                        $icon->write();
                        echo "  Created Icon: " . $iconTitle . " in Group: " . $groupTitle . "\n";
                    }
                }
            }
        }
    }

}