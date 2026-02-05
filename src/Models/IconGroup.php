<?php

namespace XD\IconSelectField\Models;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use XD\IconSelectField\Forms\IconSelectField;

class IconGroup extends DataObject
{
    private static $table_name = 'IconSelectField_IconGroup';

    private static $db = [
        'Title' => 'Varchar',
        'Sort' => 'Int',
    ];

    private static $has_many = [
        'Icons' => Icon::class,
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'Icons.Count' => 'Icons',
    ];

    private static $default_sort = 'Sort ASC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Sort', 'Icons']);

        // add Icons field to Root.Main tab
        $config = GridFieldConfig_RecordEditor::create();
        $config->addComponent(new GridFieldOrderableRows('Sort'));

        $field = GridField::create('Icons', _t(__CLASS__ . '.Icons', 'Icons'), $this->Icons(), $config);
        $fields->addFieldToTab('Root.Main', $field);

        return $fields;
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
            foreach ($icons as $groupTitle => $iconList) {
                $iconGroup = IconGroup::get()->filter('Title', $groupTitle)->first();
                if (!$iconGroup) {
                    $iconGroup = IconGroup::create();
                    $iconGroup->Title = $groupTitle;
                    $iconGroup->Sort = $groupSort++;
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