var button = document.getElementById('home-intro-section__button');

button.addEventListener('mousedown', function() {
    this.style.boxShadow = '0 5px 4px -2px rgba(0,0,0,.4), 0 4px 4px 0 rgba(0,0,0,.3), 0 3px 8px 0 rgba(0,0,0,.2)';
});

button.addEventListener('mouseup', function() {
    this.style.background = 'grenn';
});