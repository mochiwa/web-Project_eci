'use strict';


export default function BurgerMenu(parentSelector) {
    this.parent = parentSelector;

    Object.defineProperty(this, 'id', {
        get: () => {
            return this.parent + '-burger-id';
        }
    });
    Object.defineProperty(this, 'parentNode', {
        get: () => {
            return document.querySelector(this.parent);
        }
    });
    Object.defineProperty(this, 'node', {
        get: () => {
            return document.getElementById(this.id);
        }
    });

    Object.defineProperty(this, 'menu', {
        get: () => {
            let menu = document.createElement('div');
            menu.className = BurgerMenu.CSS['BURGER_MENU'];
            menu.id = this.id;
            for (let i = 0; i < 3; ++i) {
                let line = document.createElement('div');
                line.className = BurgerMenu.CSS['BURGER_LINE'];
                line.classList.add(BurgerMenu.CSS['BURGER_LINE'] + '-' + i);
                menu.appendChild(line);
            }

            menu.addEventListener('click', () => {
                this.toggle();
            });
            menu.classList.add(BurgerMenu.CSS['BURGER_MENU_OPEN']);

            return menu;
        }
    });
}

BurgerMenu.CSS = {
    BURGER_MENU: 'burger',
    BURGER_LINE: 'burger-line',
    BURGER_MENU_OPEN: 'burger--open',
    HIDDEN_MODIFIER: 'burger__hiddenItem'
};
/**
 * Open/Close the menu
 */
BurgerMenu.prototype.toggle = function () {
    this.node.classList.toggle(BurgerMenu.CSS['BURGER_MENU_OPEN']);

    Array.prototype.forEach.call(this.parentNode.children, e => {
        if (e.id !== this.id)
            e.classList.toggle(BurgerMenu.CSS['HIDDEN_MODIFIER']);
    });
};

BurgerMenu.prototype.show = function () {
    if (this.node === null)
        this.parentNode.prepend(this.menu);
};


BurgerMenu.prototype.hide = function () {
    Array.prototype.forEach.call(this.parentNode.children, e => {
        if (e.id !== this.id)
            e.classList.remove(BurgerMenu.CSS['HIDDEN_MODIFIER']);
    });

    if (this.node !== null)
        this.node.remove();
};

/**
 * If the screen witdh is less than maxSize print the burger menu 
 * and append a listener on the windows to show menu when screen witdh 
 * is less than maxSize 
 * @param  {[type]} maxSize Max screen size to print burger menu
 */
BurgerMenu.prototype.run = function (maxSize) {
    if (window.innerWidth < maxSize)
        this.show();

    window.addEventListener('resize', () => {
        if (window.innerWidth >= maxSize)
            this.hide();
        else
            this.show();
    });
};
