function checkBrightness(hexColor) {
    var c = hexColor.substring(1);      // strip #
    var rgb = parseInt(c, 16);   // convert rrggbb to decimal
    var r = (rgb >> 16) & 0xff;  // extract red
    var g = (rgb >>  8) & 0xff;  // extract green
    var b = (rgb >>  0) & 0xff;  // extract blue

    return 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
}

jQuery(document).ready(function($){

    var colorPickerMan = $('#color-picker-man');
    var colorPickerWoman = $('#color-picker-woman');
    var colorPickerNogender = $('#color-picker-nogender');

    if(colorPickerMan.val() != '') {
        var bgColor = colorPickerMan.val();
        var textColor = '#fff';
        if(checkBrightness(bgColor) > 128) {
            textColor = '#000'
        }
        colorPickerMan.css({
            'background-color': bgColor,
            'color': textColor
        });
    }
    if(colorPickerWoman.val() != '') {
        var bgColor = colorPickerWoman.val();
        var textColor = '#fff';
        if(checkBrightness(bgColor) > 128) {
            textColor = '#000'
        }
        colorPickerWoman.css({
            'background-color': bgColor,
            'color': textColor
        });
    }
    if(colorPickerNogender.val() != '') {
        var bgColor = colorPickerNogender.val();
        var textColor = '#fff';
        if(checkBrightness(bgColor) > 128) {
            textColor = '#000'
        }
        colorPickerNogender.css({
            'background-color': bgColor,
            'color': textColor
        });
    }

    colorPickerMan.iris({
        hide: false,
        palettes: ['#99a9be', '#6b7cbe', '#3e62be', '#2650be', '#2a4fbe', '#0011be'],
        change: function(e, ui) {
            var bgColor = ui.color.toString();
            var textColor = '#fff';
            if(checkBrightness(bgColor) > 128) {
                textColor = '#000'
            }
            $(this).css({
                'background-color': bgColor,
                'color': textColor
            });
        }
    });
    colorPickerWoman.iris({
        hide: false,
        palettes: ['#bb94be', '#bc75be', '#be50be', '#be37bd', '#be1abc', '#9a00be'],
        change: function(e, ui) {
            var bgColor = ui.color.toString();
            var textColor = '#fff';
            if(checkBrightness(bgColor) > 128) {
                textColor = '#000'
            }
            $(this).css({
                'background-color': bgColor,
                'color': textColor
            });
        }
    });
    colorPickerNogender.iris({
        hide: false,
        palettes: ['#cdcdcd', '#9b9b9b', '#7f7f7f', '#4c4c4c', '#2b2b2b', '#1b1b1b'],
        change: function(e, ui) {
            var bgColor = ui.color.toString();
            var textColor = '#fff';
            if(checkBrightness(bgColor) > 128) {
                textColor = '#000'
            }
            $(this).css({
                'background-color': bgColor,
                'color': textColor
            });
        }
    });
});