var activate = false;
var HeaderNav = document.getElementsByClassName('hidden_header_nav')[0];
var menuButton = document.getElementById('menu_button');

function displayHiddenMenu(){
    if (!activate){
        window.activate = true;
        HeaderNav.style.transform = 'translateX(0)';
        menuButton.innerHTML = '&#x274C;'; // устанавливаем юникод крестик
    } else {
        window.activate = false;
        HeaderNav.style.transform = 'translateX(100%)'; 
        menuButton.innerHTML = '&#9776;';
    }
}

function hideMenu(){
    if (document.documentElement.clientWidth >= 420) {
        window.activate = false;
        HeaderNav.style.transform = 'translateX(100%)'; 
        menuButton.innerHTML = '&#9776;'; 
    }
}

window.addEventListener('resize', hideMenu);
hideMenu();