<?php
namespace Offset;

/**
 * Manage Offset packs
 */
class Packs
{
    private static $instance = null;
    public $packs = array();

    /**
     * Init
     */
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'hookActionLoadPacks'), 50, 0);
        add_action('admin_enqueue_scripts', array($this, 'hookActionAdminEnqueueScripts'), 50, 0);
        add_action('wp_enqueue_scripts', array($this, 'hookActionWPEnqueueScripts'), 50, 0);
    }

    /**
     * Get instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Packs();
        }

        return self::$instance;
    }

    /**
     * Adds an "Offset" block pack
     *
     * @param Pack $pack A pack created with "OffsetPack".
     * @return boolean
     */
    public function addPack(Pack $pack = null)
    {
        if (empty($pack) || !$pack->isValide()) {
            return false;
        }

        $this->packs[] = $pack;

        return true;
    }

    /**
     * Loads the registered packs
     */
    public function hookActionLoadPacks()
    {
        $folders_blacklist = array(
            '..',
            '.',
        );

        $blocks_paths = array();

        foreach ($this->packs as $pack) {
            if (empty($pack->dir)) {
                continue;
            }

            $packs_blocks_path = array_diff(scandir($pack->dir), $folders_blacklist);

            foreach ($packs_blocks_path as $file_or_folder_name) {
                if (!is_dir($pack->dir . DIRECTORY_SEPARATOR . $file_or_folder_name)) {
                    continue;
                }

                if (!file_exists($pack->dir . DIRECTORY_SEPARATOR . $file_or_folder_name . DIRECTORY_SEPARATOR . $file_or_folder_name . '.php')) {
                    continue;
                }

                $blocks_paths[] = $pack->dir . DIRECTORY_SEPARATOR . $file_or_folder_name . DIRECTORY_SEPARATOR . $file_or_folder_name . '.php';
            }
        }

        foreach ($blocks_paths as $block_path) {
            include $block_path;
        }
    }

    /**
     * Adds styles and dependent scripts to packages in the admin
     */
    public function hookActionAdminEnqueueScripts()
    {
        global $current_screen;

        if (empty($current_screen) || !method_exists($current_screen, 'is_block_editor') || !$current_screen->is_block_editor()) {
            return false;
        }

        $styles = array();
        $scripts = array();

        $style_default = array(
            'handle' => '',
            'src' => '',
            'deps' => array(),
            'ver' => false,
            'media' => 'all',
        );

        $script_default = array(
            'handle' => '',
            'src' => '',
            'deps' => array(),
            'ver' => false,
            'in_footer' => false,
        );

        foreach ($this->packs as $pack) {
            if (!is_array($pack->editor_styles) || !is_array($pack->editor_scripts)) {
                continue;
            }

            foreach ($pack->editor_styles as $style) {
                if (!is_array($style)) {
                    continue;
                }

                $styles[] = array_merge($style_default, $style);
            }

            foreach ($pack->editor_scripts as $script) {
                if (!is_array($script)) {
                    continue;
                }

                $scripts[] = array_merge($script_default, $script);
            }
        }

        foreach ($styles as $style) {
            wp_enqueue_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
        }

        foreach ($scripts as $script) {
            wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);
        }
    }

    /**
     * Adds styles and dependent scripts to packages
     */
    public function hookActionWPEnqueueScripts()
    {
        $packs = array_map(function ($pack) {
            return $pack->name ?? '';
        }, $this->packs);

        $is_style_enqueue_all_packs = (bool) apply_filters('offset_packs_is_styles_enqueue', true);
        $is_script_enqueue_all_packs = (bool) apply_filters('offset_packs_is_scripts_enqueue', true);

        $packs_styles = (array) apply_filters('offset_packs_styles', $packs);
        $packs_scripts = (array) apply_filters('offset_packs_scripts', $packs);

        $styles = array();
        $scripts = array();

        $style_default = array(
            'handle' => '',
            'src' => '',
            'deps' => array(),
            'ver' => false,
            'media' => 'all',
        );

        $script_default = array(
            'handle' => '',
            'src' => '',
            'deps' => array(),
            'ver' => false,
            'in_footer' => false,
        );

        foreach ($this->packs as $pack) {
            if (!is_array($pack->styles) || !is_array($pack->scripts)) {
                continue;
            }

            foreach ($pack->styles as $style) {
                if (!is_array($style) || !$is_style_enqueue_all_packs || !in_array($pack->name, $packs_styles)) {
                    continue;
                }

                $styles[] = array_merge($style_default, $style);
            }

            foreach ($pack->scripts as $script) {
                if (!is_array($script) || !$is_script_enqueue_all_packs || !in_array($pack->name, $packs_scripts)) {
                    continue;
                }

                $scripts[] = array_merge($script_default, $script);
            }
        }

        $styles = (array) apply_filters('offset_packs_styles_enqueued', $styles);
        $scripts = (array) apply_filters('offset_packs_scripts_enqueued', $scripts);

        foreach ($styles as $style) {
            wp_enqueue_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
        }

        foreach ($scripts as $script) {
            wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);
        }
    }
}
