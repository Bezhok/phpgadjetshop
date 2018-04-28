// Адаптация пагинации
var pagination_buttons = document.getElementsByClassName('pagination_number');
var pagination_length = pagination_buttons.length*58;
var pagination = document.getElementById('schema_all_products_pagination');
var pagination_preview = document.getElementById('pagination_preview');
var pagination_next = document.getElementById('pagination_next');
var nextNpreSection = document.getElementById('pagination_nextNpre_section');
var product = document.getElementsByClassName('schema_product_main');

if (! product.length) {
    document.getElementsByClassName('schema_all_products')[0].innerHTML =
        "<p align='center'><font size='4px'><b>Нет товаров по выбранному критерию.</b><br /><a href='/'>Перейти на главную</a></font></p>";
};

function adapt_pagination() {
    if($(window).width() < 828)
    {
        pagination.style.maxWidth = pagination_length + 'px';

        if ( ! $( '#pagination_nextNpre_section' ).has( '#pagination_preview' ).length && pagination_preview){
            pagination.removeChild(pagination_preview);
            nextNpreSection.appendChild(pagination_preview);
        }
        if ( ! $( '#pagination_nextNpre_section' ).has( '#pagination_next' ).length && pagination_next){
            pagination.removeChild(pagination_next);
            nextNpreSection.appendChild(pagination_next);
        }

    }
    else if ($(window).width() > 828) {
        pagination.style.maxWidth = '';

        if ($( '#pagination_nextNpre_section' ).has( '#pagination_preview' ).length && pagination_preview) {
            nextNpreSection.removeChild(pagination_preview);
            pagination.insertBefore(pagination_preview, pagination.firstChild);
        }
        if ($( '#pagination_nextNpre_section' ).has( '#pagination_next' ).length && pagination_next) {
            nextNpreSection.removeChild(pagination_next);
            pagination.insertBefore(pagination_next, nextNpreSection);
        }

    }
};

adapt_pagination();

window.addEventListener('resize', adapt_pagination);
// $(window).resize(function() {
//     adapt_pagination();
// });
