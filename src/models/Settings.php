<?php
/**
 * Colour Pal plugin for Craft CMS 3.x
 *
 * A friendly palette picker field with predefined colour palette configuration.
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2018 Isaac Gray
 */

namespace swixpop\colourpal\models;

use swixpop\colourpal\ColourPal;

use Craft;
use craft\base\Model;

/**
 * ColourPal Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Isaac Gray
 * @package   ColourPal
 * @since     0.9.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $someAttribute = 'Some Default';

    public $defaultPalette = 'default';
    public $palettes = [
        'default' => [
            'name' => 'Default',
            'colours' => []
        ]
    ];

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
