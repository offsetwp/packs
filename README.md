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
