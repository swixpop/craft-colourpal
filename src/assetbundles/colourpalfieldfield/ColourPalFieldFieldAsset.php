<?php
/**
 * Colour Pal plugin for Craft CMS 3.x
 *
 * A friendly palette picker field with predefined colour palette configuration.
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2018 Isaac Gray
 */

namespace swixpop\colourpal\assetbundles\colourpalfieldfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
//use craft\web\assets\selectize\SelectizeAsset;

/**
 * ColourPalFieldFieldAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Isaac Gray
 * @package   ColourPal
 * @since     0.9.0
 */
class ColourPalFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@swixpop/colourpal/assetbundles/colourpalfieldfield/build";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
//            SelectizeAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'ColourPalField.js',
        ];

        $this->css = [
            'ColourPalField.css',
        ];

        parent::init();
    }
}
