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

                $(_this.element).find('.select').addClass('selectize');
                var selectize;

                var $select = $(_this.element).find('select').selectize({
                    render: {
                        option: function(data, escape) {
                            console.log(data);
                            var value = JSON.parse(data.value);

                            return '<div class="option ColourPal__option" data-selectable="" data-value="{"colourName&quot;"' + value.colourName + '","cssValue":"' + value.cssValue + '"}"><span class="ColourPal__text">' + data.text + '</span><div class="ColourPal__swatch" style="background:' + value.cssValue + ';"></div><div class="ColourPal__bg" style="background:' + value.cssValue + ';"></div></div>';
                        },
                        item: function(data, escape) {
                            console.log(data);
                            var value = JSON.parse(data.value);

                            return '<div class="item ColourPal__item" data-selectable="" data-value="{"colourName&quot;"' + value.colourName + '","cssValue":"' + value.cssValue + '"}"><span class="ColourPal__text">' + data.text + '</span><div class="ColourPal__swatch" style="background:' + value.cssValue + ';"></div><div class="ColourPal__bg" style="background:' + value.cssValue + ';"></div></div>';

                        }
                    }
                });

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
