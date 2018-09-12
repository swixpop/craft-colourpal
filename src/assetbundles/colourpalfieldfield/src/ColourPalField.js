require('choices.js/assets/styles/css/choices.css');
require('./ColourPalField.scss');
var Choices = require('choices.js');

/**
 * Colour Pal plugin for Craft CMS
 *
 * ColourPalField Field JS
 *
 * @author    Isaac Gray
 * @copyright Copyright (c) 2018 Isaac Gray
 * @link      https://www.vaersaagod.no
 * @package   ColourPal
 * @since     0.9.0ColourPalColourPalField
 */



;(function ( $, window, document, undefined ) {

    var pluginName = "ColourPalColourPalField",
        defaults = {
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {

                var templateCallback = function(template) {
                    // console.log('templateCallback', this);
                    var config = this.config;
                    var classNames = config.classNames;

                    return {
                        item: function(data) {
                            if (data.placeholder) {
                                return template('<div class="' + classNames.placeholder + '">' + data.value + '</div>');
                            }

                            var cssValue = JSON.parse(data.value || '{"cssValue": "0"}').cssValue;
                            if (config.removeItemButton ) {
                                return template('<div class="ColourPal__item ColourPal__item--deletable ' + classNames.item + ' ' + (data.highlighted ? classNames.highlightedState : '') + ' ' + (!data.disabled ? classNames.itemSelectable : '') + '" data-item data-id="' + data.id + '" data-value="' + data.value + '" ' + (data.active ? 'aria-selected="true"' : '') + ' ' + (data.disabled ? 'aria-disabled="true"' : '') + ' data-deletable><span class="ColourPal__text">' + data.label + '</span><div class="ColourPal__swatch" style="background:' + cssValue + ';"></div><button class="' + classNames.button + '" data-button>Remove item</button> </div>');
                            }
                            return template('<div class="ColourPal__item ' + classNames.item + ' ' + (data.highlighted ? classNames.highlightedState : classNames.itemSelectable) + '" data-item data-id="' + data.id + '" data-value="' + data.value + '" ' + (data.active ? 'aria-selected="true"' : '') + ' ' + (data.disabled ? 'aria-disabled="true"' : '') + '><span class="ColourPal__text">' + data.label + '</span><div class="ColourPal__swatch" style="background:' + cssValue + ';"></div></div>');
                        },
                        choice: function(data) {
                            if (data.placeholder) {
                                return template('<div class="' + classNames.placeholder + '">' + data.value + '</div>');
                            }
                            var cssValue = JSON.parse(data.value || '{"cssValue": "0"}').cssValue;
                            return template('<div class="ColourPal__choice ' + classNames.item + ' ' + classNames.itemChoice + ' ' + (data.disabled ? classNames.itemDisabled : classNames.itemSelectable) + '" data-select-text="' + config.itemSelectText + '" data-choice ' + (data.disabled ? 'data-choice-disabled aria-disabled="true"' : 'data-choice-selectable') + ' data-id="' + data.id + '" data-value="' + data.value + '" ' + (data.groupId > 0 ? 'role="treeitem"' : 'role="option"') + '><span class="ColourPal__text">' + data.label + '</span><div class="ColourPal__swatch" style="background:' + cssValue + ';"></div><div class="ColourPal__bg" style="background:' + cssValue + ';"></div></div>');
                        }
                    };
                };
                console.log(_this.options.allowBlank === '1');
                var options = [];

                var selectElement = $(_this.element).find('.ColourPal__select-default select');
                var choicesElement = $(_this.element).find('.ColourPal__select')[0];

                var choices = new Choices(choicesElement, {
                    maxItemCount: 1,
                    searchEnabled: false,
                    shouldSort: _this.options.sortByName === '1',
                    removeItemButton: _this.options.allowBlank === '1',
                    callbackOnCreateTemplates: templateCallback,
                    classNames: {
                        listSingle: 'ColourPal__list choices__list--single',
                    }
                });

                choicesElement.addEventListener('addItem', function(e) {
                    console.log('addItem', e);
                    if (!choices.getValue().placeholder) {
                        var selectedValue = e.detail.value;
                        selectElement.val(selectedValue);
                    }

                }, false);

                if (_this.options.allowBlank === '1') {
                    choicesElement.addEventListener('removeItem', function(e) {
                        console.log('removeItem');
                        if (!choices.getValue()) {
                            selectElement.val(JSON.stringify({colourName:'',cssValue:''}));
                        }
                    }, false);
                };


                // $(_this.element).find('.select').addClass('selectize');
                // var selectize;
                //
                // var $select = $(_this.element).find('select').selectize({
                //     render: {
                //         option: function(data, escape) {
                //             console.log(data);
                //             var value = JSON.parse(data.value);
                //
                //             return '<div class="option ColourPal__option" data-selectable="" data-value="{"colourName&quot;"' + value.colourName + '","cssValue":"' + value.cssValue + '"}"><span class="ColourPal__text">' + data.text + '</span><div class="ColourPal__swatch" style="background:' + value.cssValue + ';"></div><div class="ColourPal__bg" style="background:' + value.cssValue + ';"></div></div>';
                //         },
                //         item: function(data, escape) {
                //             console.log(data);
                //             var value = JSON.parse(data.value);
                //
                //             return '<div class="item ColourPal__item" data-selectable="" data-value="{"colourName&quot;"' + value.colourName + '","cssValue":"' + value.cssValue + '"}"><span class="ColourPal__text">' + data.text + '</span><div class="ColourPal__swatch" style="background:' + value.cssValue + ';"></div><div class="ColourPal__bg" style="background:' + value.cssValue + ';"></div></div>';
                //
                //         }
                //     }
                // });

            });
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                    new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
