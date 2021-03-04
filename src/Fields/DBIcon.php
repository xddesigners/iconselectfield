<?php

namespace XD\IconSelectField\Fields;

use SilverStripe\ORM\FieldType\DBVarchar;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;
use XD\IconSelectField\Forms\IconSelectField;

class DBIcon extends DBVarchar
{
    public function forTemplate()
    {
        $iconName = $this->getValue();
        $found = null;
        $iconGroups = IconSelectField::config()->get('icons');
        foreach ($iconGroups as $groupLabel => $iconGroup) {
            // first layer is actually an icon
            if ($iconName === $groupLabel && is_string($iconGroup)) {
                $found = $iconGroup;
                break;
            } elseif (is_array($iconGroup)) {
                foreach ($iconGroup as $label => $icon) {
                    if ($iconName === $label) {
                        $found = $icon;
                        break;
                    }
                }
            }
        }

        // todo make matching configurable in config.
        if ($found && strpos($found, 'svg') !== false) {
            return SSViewer::create('XD\IconSelectField\Icon\svg')->process(new ArrayData(['Icon' => $found]));
        } elseif ($found && strpos($found, 'fa') !== false) {
            return SSViewer::create('XD\IconSelectField\Icon\fa')->process(new ArrayData(['Icon' => $found]));
        }

        return $iconName;
    }

    /**
     * Scaffold a icon select field
     *
     * @param null $title
     * @param null $params
     * @return \SilverStripe\Forms\FormField|DBVarchar|IconSelectField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return IconSelectField::create($this->name, $title);
    }
}
