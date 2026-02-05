<?php

namespace XD\IconSelectField\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use XD\IconSelectField\Forms\IconSelectField;

/**
 * Class LeftAndMainExtension
 * @package XD\IconSelectField\Extensions
 *
 * @property LeftAndMain owner
 */
class LeftAndMainExtension extends Extension
{
    public function onInit()
    {
        $includes = IconSelectField::config()->get('fonts_include');
        if ($includes && is_array($includes)) {
            foreach ($includes as $include) {
                Requirements::insertHeadTags($include);
            }
        } elseif ($includes && is_string($includes)) {
            Requirements::insertHeadTags($includes);
        }
    }
}
