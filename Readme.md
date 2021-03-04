# Icon select field

Add a icon select field to the Silverstripe CMS.

## Installation

```bash
composer require xddesigners/iconselectfield
```

## Usage

Add a icon field to your db. This field will automaticly scaffold a `IconSelectField`.

```php
class YourClass extends DataObject
{
  private static $db = [
    'Icon' => 'Icon'
  ];

  public function getCMSFields()
  {
    $fields = parent::getCMSFields();
    $fields->addFieldsToTab('Root.Main', [
      // this field is automaticly scaffolded
      IconSelectField::create('Icon', 'Icon')
    ]);

    $fields;
  }
}
```

In your template you can now access the Icon property and this will be replaced by the selected icon.

```html
$Icon
```

You can confugure the icons you want to use trough a yml config.

```yml
---
Name: vivabasicIcons
---
XD\IconSelectField\Forms\IconSelectField:
  fonts_include:
  # add your own link to a font awesome version your using
    - '<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">'
  icons:
    system:
      arrow-right: 'fas fa-arrow-right'
      arrow-left: 'fas fa-arrow-left'
      custom-icon: '<svg />' # you can also configure custom svg icons
```
