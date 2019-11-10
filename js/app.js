console.log('%cFOUND 0.2', 'padding:5px;color: #fff; background: #c3e88d;');

/*------------------------------------------------------------------
Dependencies
------------------------------------------------------------------*/

import $ from 'jquery';

/*------------------------------------------------------------------
Stylesheets
------------------------------------------------------------------*/

import './../scss/style.scss';

/*------------------------------------------------------------------
Variables
------------------------------------------------------------------*/

let $body = $('body');

/*------------------------------------------------------------------
Post load classlist
------------------------------------------------------------------*/

const updateFoundTitle = (value) => {
    let $foundTitle = $('.js-found-preview-title');
    if ($('.js-found-title-character-limit').length < 1) {
        $('<div class="[ js-found-title-character-limit ]" data-limit="100">100</div>').insertAfter($('input.js-found-title'));
    }
    if (!value) {
        $foundTitle.text(`${$foundTitle.attr('data-nominal')}`);
        $('.js-found-title-character-limit').text('100');
    } else {
        $foundTitle.text(`${value}${$foundTitle.attr('data-append')}`);
        $('.js-found-title-character-limit').text(100 - value.length);
    }
};

const updateFoundDescription = (value) => {
    let $foundDescription = $('.js-found-preview-description');
    if ($('.js-found-description-character-limit').length < 1) {
        $('<div class="[ js-found-description-character-limit ]" data-limit="400">400</div>').insertAfter($('textarea.js-found-description'));
    }
    if (!value) {
        $foundDescription.text(`${$foundDescription.attr('data-nominal')}`);
        $('.js-found-description-character-limit').text('400');
    } else {
        $foundDescription.text(value);
        $('.js-found-description-character-limit').text(400 - value.length);
    }
};

(function ($) {
    $.entwine(function ($) {

        $('.js-found-description').entwine({
            onmatch: function () {

                updateFoundDescription();
                $body.on('keyup', 'textarea.js-found-description', function () {
                    updateFoundDescription($(this).val());
                });
            }
        });

        $('.js-found-title').entwine({
            onmatch: function () {
                updateFoundTitle();
                $body.on('keyup', 'input.js-found-title', function () {
                    updateFoundTitle($(this).val());
                });

            }
        });
    });
})(jQuery);