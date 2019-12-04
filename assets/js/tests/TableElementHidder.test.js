'use strict';

const TableHidder = require('../TableElementHidder');

test('hideElements should remove class from element', () => {
	 // Set up our document body
  document.body.innerHTML =
    '<div>' +
    '  <span class="class-a class-to-hide"/>' +
    '</div>';


    TableHidder.hide('class-to-hide');

    expect(document.getElementsByClassName('class-to-hide--hidden').not.toBeNull());

});