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
    if (!value) return;
    let $foundTitle = $('.js-found-preview-title');
    let text = `${value}${$foundTitle.attr('data-append')}`;
    $foundTitle.text(text);
};

const updateFoundDescription = (value) => {
    if (!value) return;
    let $foundDescription = $('.js-found-preview-description');
    let text = `${value}${$foundDescription.attr('data-append')}`;
    $foundDescription.text(text);
};

(function ($) {
    $(document).ready(function () {

        $('<div class="[ js-found-title-character-limit ]" data-limit="100">100</div>').insertAfter($('input.js-found-title'))

        $body.on('keyup', 'input.js-found-title', function () {
            updateFoundTitle($(this).val());
        });

        $('<div class="[ js-found-description-character-limit ]" data-limit="400">400</div>').insertAfter($('textarea.js-found-description'))

        $body.on('keyup', 'textarea.js-found-description', function () {
            updateFoundDescription($(this).val());
        });
    });
})(jQuery);