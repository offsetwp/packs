<?php
namespace Offset;

/**
 * Create Offset packs
 */
class Pack
{
    public $title = '';

    public $name = '';

    public $version = '';

    public $dir = '';

    public $url = '';

    public $editor_styles = array();

    public $editor_scripts = array();

    public $styles = array();

    public $scripts = array();

    public function isValide() {
        return !empty($this->name) && !empty($this->dir);
    }
}
