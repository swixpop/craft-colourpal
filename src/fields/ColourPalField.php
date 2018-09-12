<?php
/**
 * Colour Pal plugin for Craft CMS 3.x
 *
 * A friendly palette picker field with predefined colour palette configuration.
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2018 Isaac Gray
 */

namespace swixpop\colourpal\fields;

use swixpop\colourpal\ColourPal;
use swixpop\colourpal\assetbundles\colourpalfieldfield\ColourPalFieldFieldAsset;
use swixpop\colourpal\models\ColourPalModel;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * ColourPalField Field
 *
 * Whenever someone creates a new field in Craft, they must specify what
 * type of field it is. The system comes with a handful of field types baked in,
 * and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Isaac Gray
 * @package   ColourPal
 * @since     0.9.0
 */
class ColourPalField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */


    public $palette = 'default';
    public $allowBlank;
    public $sortByName;




    // Static Methods
    // =========================================================================

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('colour-pal', 'ColourPalField');
    }

    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['palette', 'string'],
            ['palette', 'default', 'value' => 'default'],
        ]);
        return $rules;
    }

    /**
     * @return string
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }


    /**
     * @param $value
     * @param ElementInterface|null $element
     * @return mixed|ColourPalModel
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof ColourPalModel) {
            return $value;
        }

        $attr = [];

        if (is_string($value)) {
            $attr += array_filter(json_decode($value, true) ?: [],
                function ($key) {
                    return in_array($key, ['colourName', 'cssValue']);
                }, ARRAY_FILTER_USE_KEY);
        } else if (is_array($value)) {
            $attr += [
                'colourName' => $value['colourName'] ?? null,
                'cssValue' => $value['cssValue'] ?? null
            ];
        }



        return new ColourPalModel($attr);
    }


    /**
     * @param $value
     * @param ElementInterface|null $element
     * @return array|mixed|null|string
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }


    /**
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml()
    {
        $palettes = ColourPal::getInstance()->getSettings()->palettes;

        $defaultSortByName = ColourPal::getInstance()->getSettings()->defaultSortByName;
        $defaultAllowBlank = ColourPal::getInstance()->getSettings()->defaultAllowBlank;
        $fieldOptions = [];

        foreach ($palettes as $key => $value) {
            $fieldOptions[] = [
                'value' => $key,
                'label' => $value['name'] ?? $key
            ];
        }

//        Craft::dd($this->sortByName);

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'colour-pal/_components/fields/ColourPalField_settings',
            [
                'field' => $this,
                'fieldOptions' => $fieldOptions,
                'allowBlank' => (bool)($this->allowBlank ?? $defaultAllowBlank),
                'sortByName' => (bool)($this->sortByName ?? $defaultSortByName),
            ]
        );
    }


    /**
     * @param $value
     * @param ElementInterface|null $element
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle

        Craft::$app->getView()->registerAssetBundle(ColourPalFieldFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        $palette = $this->getSettings()['palette'] ? $this->getSettings()['palette'] : ColourPal::getInstance()->getSettings()->defaultPalette;
        $allowBlank = $this->getSettings()['allowBlank'] ?? ColourPal::getInstance()->getSettings()->defaultAllowBlank;
        $sortByName = $this->getSettings()['$sortByName'] ?? ColourPal::getInstance()->getSettings()->defaultSortByName;

        $colours = ColourPal::getInstance()->getSettings()->palettes[$palette]['colours'];


        $fieldOptions = [];

        if ((bool)$allowBlank) {
            $fieldOptions[] = [
                'label' => 'Select a colour...',
                'value' => Json::encode([
                    'colourName' => '',
                    'cssValue' => ''
                ]),
                'placeholder' => true
            ];
        }

        foreach ($colours as $color) {
            $currentValue = [
                'colourName' => $color['name'],
                'cssValue' => $color['value']
            ];

            $fieldOptions[] = [
                'label' => $color['label'],
                'value' => Json::encode($currentValue),
            ];
        }

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'allowBlank' => $allowBlank,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'sortByName' => $sortByName,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').ColourPalColourPalField(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'colour-pal/_components/fields/ColourPalField_input',
            [
                'name' => $this->handle,
                'value' => Json::encode($value),
                'field' => $this,
                'id' => $id,
                'fieldOptions' => $fieldOptions,
                'namespacedId' => $namespacedId,
                'allowBlank' => $allowBlank
            ]
        );
    }
}
