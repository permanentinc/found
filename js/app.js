console.log('%cFOUND 0.1', 'padding:5px;color: #fff; background: #377cff;');

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
        $('.js-found-title-character-limit').text('400');
    } else {
        $foundDescription.text(`${value}${$foundDescription.attr('data-append')}`);
        $('.js-found-description-character-limit').text(100 - value.length);
    }
};

(function ($) {
    $(document).ready(function () {



        $body.on('keyup', 'input.js-found-title', function () {
            updateFoundTitle($(this).val());
        });



        $body.on('keyup', 'textarea.js-found-description', function () {
            updateFoundDescription($(this).val());
        });
    });
})(jQuery);