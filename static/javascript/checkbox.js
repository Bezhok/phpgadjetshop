var checkboxs = document.getElementsByClassName('checkbox');

for (var i = 1; i <= checkboxs.length - 1; i++) { // начинаем с 1, потому что у самого списка такой класс
    checkboxs[i].parentNode.appendChild(checkbox()); 
    checkboxs[i].parentNode.appendChild(document.createElement('span')).innerHTML = year() -i+1;
}

function year(){
    var d = new Date()
    return d.getFullYear();
}

function checkbox(){
    var box = document.createElement('section');
    box.className = 'custom_check_box';
    return box
}