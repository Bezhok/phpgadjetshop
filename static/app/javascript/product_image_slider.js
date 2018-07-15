function getWidth(element){
    return Math.ceil(parseFloat(getComputedStyle(element).width));
}

var sliderContainer = document.getElementsByClassName('slider_container')[0];
var slider = document.getElementById('slider');
var block = document.getElementsByClassName('block_product'); // для img
var oneBlockLength = getWidth(block[0]) + parseInt(getComputedStyle(block[0]).marginLeft)*2;
slider.style.width = block.length * oneBlockLength + 'px'; // Устанавливаем правильную длину

// адаптация слайдер. Чтобы конечный блок слайдера был в самом конце
// stopTranslateXValue - значение, после которого слайдер не будет двигаться влево
function adaptSlider() {
    switch( getWidth(sliderContainer) ) {
        case 1109:
            window.stopTranslateXValue = (block.length-10) * oneBlockLength;
            break;
        case 888:
            window.stopTranslateXValue = (block.length-8) * oneBlockLength;
            break;
        case 666:
            window.stopTranslateXValue = (block.length-6) * oneBlockLength;
            break;
        case 444:
            window.stopTranslateXValue = (block.length-4) * oneBlockLength;
            break;
        case 222:
            window.stopTranslateXValue = (block.length-2) * oneBlockLength;
            break;
        default:
            window.stopTranslateXValue = (block.length-1) * oneBlockLength;
    };
};

var sliderTransformXValue = 0;

function sliderWidth(){ return parseInt(slider.style.width) };
function sliderContainerWidth(){ return parseInt(getComputedStyle(sliderContainer).width) }; 
// чтобы перелистывание назад работало только, если ширина меньше контейнера


// Left button
function previousBlock() {
    var first = true;
    if (sliderTransformXValue == 0 && sliderWidth() > sliderContainerWidth()) {
        slider.style.transform = 'translateX(' + ( - stopTranslateXValue  ) + 'px)';
        window.sliderTransformXValue =  - stopTranslateXValue;
        first = false;
    };
    if (sliderTransformXValue != 0 && first) {
        slider.style.transform = 'translateX(' + ( sliderTransformXValue + oneBlockLength ) + 'px)';
        window.sliderTransformXValue = sliderTransformXValue + oneBlockLength;
    };

};

// Right button
function nextBlock() {
    var notlast = true;
    if ( Math.abs(sliderTransformXValue) == stopTranslateXValue ) {
        slider.style.transform = 'translateX(0px)';
        window.sliderTransformXValue = 0;
        notlast = false;
    };
    if ( Math.abs(sliderTransformXValue) < stopTranslateXValue && notlast ) {
        slider.style.transform = 'translateX(' + ( sliderTransformXValue - oneBlockLength ) + 'px)';
        window.sliderTransformXValue = sliderTransformXValue - oneBlockLength;
    };
};


window.addEventListener('resize', adaptSlider);
adaptSlider();


// выделение элемента
var selected;
var imagesBox = document.getElementById('images_box');

function identifiesObject(element) {
    var eventObject = element.target;
    var objectTag = eventObject.tagName;

    while (objectTag != 'LI' && objectTag != 'UL') { //если это не li или ul, поднимаемся вверх до родителя этого элемента
        eventObject = eventObject.parentNode;
        objectTag = eventObject.tagName;

        if ( eventObject.tagName == 'LI') {
            makeBorderBlack(eventObject);
            viewsImage(eventObject);
            break;
        };
    };
};

function makeBorderBlack(eventObject) {
    if (selected) {
        selected.style.borderColor = 'white';
    };
    selected = eventObject;
    selected.style.borderColor = 'black';
};
function viewsImage(eventObject) {
    var imageSrc = eventObject.childNodes[2].src;
    imagesBox.style.display = 'block';
    imagesBox.childNodes[0].src = imageSrc;
}

slider.onclick = identifiesObject;