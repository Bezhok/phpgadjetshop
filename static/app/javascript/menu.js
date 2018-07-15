var activate = false;
var HeaderNav = document.getElementsByClassName('hidden_header_nav')[0];
var menuButton = document.getElementById('menu_button');


var elementChildrens = $('.header_nav').clone().children();
for (var i=0; i < elementChildrens.length; i++) {
    HeaderNav.appendChild(elementChildrens[i]);
}

function displayHiddenMenu(){
    if (!activate){
        window.activate = true;
        HeaderNav.style.transform = 'translateX(0)';
        menuButton.innerHTML = '<div>&#x274C;</div>'; // устанавливаем юникод крестик
    } else {
        window.activate = false;
        HeaderNav.style.transform = 'translateX(100%)'; 
        menuButton.innerHTML = '<div>&#9776;</div>';
    }
}

// function hideMenu(){
//     if (document.documentElement.clientWidth >= 992) {
//         window.activate = false;
//         HeaderNav.style.transform = 'translateX(0)'; 
//         menuButton.innerHTML = '&#9776;'; 
//     }
// }

// window.addEventListener('resize', hideMenu);
// hideMenu();