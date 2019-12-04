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

const toggleTypeSidebar = () => {
    $('body').toggleClass('foundOpen');
};

$('.js-toggle-found').on('click', function (e) {
    e.preventDefault();
    toggleTypeSidebar();
});



const saveFound = () => {
    $('body').addClass('foundBusy');
    $.ajax({
        url: $('.js-save-type-settings').attr('data-api'),
        type: 'POST',
        data: {  }
    }).done(function (response) {
        setTimeout(() => $('body').removeClass('foundBusy'), 400);
        $(window).trigger('resize');
    });
};

$('.js-save-type-settings').on('click', function () {
    saveFound();
});