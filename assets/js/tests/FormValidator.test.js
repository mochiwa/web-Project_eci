'use strict';

const sum = require('../FormValidator');

test('constructor should throw exception when FormId Not Found', () => {
	expect(()=>{
		validator=new module.default('notExistingForm');
	}).toThrow();
});