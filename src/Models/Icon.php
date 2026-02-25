<?php

namespace XD\IconSelectField\Models;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;

class Icon extends DataObject
{
    private static $table_name = 'IconSelectField_Icon';

    private static $db = [
        'Title' => 'Varchar',
        'Value' => 'Varchar',
        'SVG' => 'Text',
        'Sort' => 'Int',
    ];

    private static $has_one = [
        'IconGroup' => IconGroup::class,
        'Image' => Image::class,
    ];

    private static $owns = [
        'Image',
    ];

    private static $default_sort = 'Sort ASC';

    private static $summary_fields = [
        'Title' => 'Title',
        'Preview' => 'Preview',
        'IconGroup.Title' => 'IconGroup',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Sort', 'IconGroupID']);

        $fields->dataFieldByName('Value')
            ->setTitle(_t(__CLASS__ . '.Value', 'Icon Class'))
            ->setDescription(_t(__CLASS__ . '.ValueDescription', 'The CSS class for the icon, e.g. "fa-home", "fa-arrow-right", etc.'));

        $fields->addFieldsToTab('Root.Main',[
            HeaderField::create('PreviewHeader', 'Preview'),
            LiteralField::create('PreviewField', '<div style="width: 100px;">' . $this->getPreview()->forTemplate() . '</div>')
                ->setTitle('Preview'),
                ]
        );

        return $fields;
    }

    public function forTemplate(): string
    {
        return $this->getPreview()->forTemplate();
    }

    public function getPreview()
    {

        if($this->ImageID){
            return $this->Image()->Fill(72,72);
        }

        if ($this->SVG) {
            return DBHTMLText::create()->setValue($this->SVG);
        }

        $group = $this->IconGroup();

        $classes = trim(implode(' ', array_filter([
            $group?->IconClass,
            $group?->IconStyle,
            $this->Value,
        ])));

        return DBHTMLText::create()->setValue(
            sprintf('<i class="%s"></i>', $classes)
        );
    }

    public function onBeforeWrite ()
    {
        parent::onBeforeWrite();
        $this->Title = strtolower($this->Title);
    }

}