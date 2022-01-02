# Packs - Create Offset packs

## Installation

```bash
composer require offset/packs
```

## Use

For create a pack

```php
use Offset\Pack;
use Offset\Packs;

class PackDemo extends Pack
{
    public $title = 'Demo';
    public $name = 'demo';
    public $version = '1.0.0';
    public $dir = __DIR__ . '/blocks';
    public $url = '/wp-content/plugins/offset-demo/blocks';
    public $editor_styles = array(
        array(
            'handle' => 'offset-demo-editor-style',
            'src' => '/wp-content/plugins/offset-demo/assets/css/admin.css',
        ),
    );
    public $editor_scripts = array(
        array(
            'handle' => 'offset-demo-editor-script',
            'src' => '/wp-content/plugins/offset-demo/assets/js/admin.js',
        ),
    );
    public $styles = array(
        array(
            'handle' => 'offset-demo-style',
            'src' => '/wp-content/plugins/offset-demo/assets/css/style.css',
        ),
    );
    public $scripts = array(
        array(
            'handle' => 'offset-demo-script',
            'src' => '/wp-content/plugins/offset-demo/assets/js/script.js',
        ),
    );
}

$packs_manager = Packs::getInstance();
$pack_demo = new PackDemo();

$packs_manager->addPack($pack_demo);
```

### Filters

- `offset_packs_is_styles_enqueue`
- `offset_packs_is_scripts_enqueue`
- `offset_packs_styles`
- `offset_packs_scripts`
- `offset_packs_styles_enqueued`
- `offset_packs_scripts_enqueued`

#### `offset_packs_is_styles_enqueue`

Removes all styles that need to be loaded with the packs

##### Example

```php
add_filter('offset_packs_is_styles_enqueue', '__return_false');
```

#### `offset_packs_is_scripts_enqueue`

Removes all scripts that need to be loaded with the packs

##### Example

```php
add_filter('offset_packs_is_scripts_enqueue', '__return_false');
```

#### `offset_packs_styles`

##### Parameters

- `$packs` - `array` - The list of packs that load styles

##### Example

```php
function packs_styles($packs) {
    return array_diff($packs, array('demo')); // Remove styles from "Demo" pack
}

add_filter('offset_packs_styles', 'packs_styles', 10, 1);
```

#### `offset_packs_scripts`

##### Parameters

- `$packs` - `array` - The list of packs that load scripts

##### Example

```php
function packs_scripts($packs) {
    return array_diff($packs, array('demo')); // Remove scripts from "Demo" pack
}

add_filter('offset_packs_scripts', 'packs_scripts', 10, 1);
```

#### `offset_packs_styles_enqueued`

##### Parameters

- `$packs_styles` - `array` - The list of styles are loaded

```php
// Remove bootstrap style load by packs
function packs_styles_enqueued($packs_styles) {
    return array_filter($packs_styles, function ($style) {
        return !is_int(mb_stripos($style['src'], 'bootstrap'));
    });
}
add_filter('offset_packs_styles_enqueued', 'packs_styles_enqueued', 10, 1);
```

#### `offset_packs_scripts_enqueued`

##### Parameters

- `$packs_scripts` - `array` - The list of scripts are loaded

```php
// Remove bootstrap script load by packs
function packs_scripts_enqueued($packs_scripts) {
    return array_filter($packs_scripts, function ($script) {
        return !is_int(mb_stripos($script['src'], 'bootstrap'));
    });
}
add_filter('offset_packs_scripts_enqueued', 'packs_scripts_enqueued', 10, 1);
```
