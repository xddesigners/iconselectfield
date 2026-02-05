<?php

namespace XD\IconSelectField\Models;

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
        if($this->SVG){
            return DBHTMLText::create()->setValue($this->SVG);
        }
        return DBHTMLText::create()->setValue('<i class="' . $this->Value . '"></i>');
    }

    public function onBeforeWrite ()
    {
        parent::onBeforeWrite();
        $this->Title = strtolower($this->Title);
    }

}