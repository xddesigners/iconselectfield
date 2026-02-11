<?php

namespace XD\IconSelectField\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use XD\IconSelectField\Models\IconGroup;

class SiteConfigExtension extends Extension
{

    public function updateCMSFields(FieldList $fields)
    {
        $config = GridFieldConfig_RecordEditor::create();
        $config->addComponent(GridFieldOrderableRows::create());
        $fields->addFieldToTab('Root.Icons',
            GridField::create('IconGroups', _t(__CLASS__ . '.IconGroups', 'Icon groups'), IconGroup::get(), $config)
        );
    }

}