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
 * ColourPal  Model
 *
 * @author    Isaac Gray
 * @package   ColourPal
 * @since     0.9.0
 */
class ColourPalModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $colourName = '';


    /**
     * @var string
     */
    public $cssValue = '';


    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['colourName', 'cssValue'], 'string'],
            [['colourName', 'cssValue'], 'default', 'value' => ''],
        ];
    }
}
