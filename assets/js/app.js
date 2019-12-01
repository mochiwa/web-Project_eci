'use strict';



function App(){
	this.topBarBurgerMenu=null;
}

/**
 * Init a burger menu for a component
 * @param  {[type]} menu           var where store the obj
 * @param  {[type]} minSize        minimum size to import the pluging
 * @param  {[type]} parentSelector the name selector of the parent (in the html)
 */
App.prototype.initBurgerMenu = function(menu,minSize,parentSelector){
	if(window.innerWidth < minSize)
		import('./burgerMenu').then(module =>{
			menu=new module.default(parentSelector);
			menu.run(minSize);
		});
	else{
		window.addEventListener('resize', ()=>{
			if(menu==null && window.innerWidth < minSize ){
				import('./burgerMenu').then(module =>{
					menu=new module.default(parentSelector);
					menu.run(minSize);
				})
			}
		});
	} 
};


App.prototype.run = function(){
	this.initBurgerMenu(this.topBarBurgerMenu,700,'#mainTopNavBar');
};

new App().run();