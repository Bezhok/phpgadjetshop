<?php 
namespace core\pagination;

class Pagination
{
    public function __construct($obj, $objs_per_page, $displayed_count_of_pages)
    {
        // обЪекты закрепляются к определнной странице
        $this->pages = ceil($obj->get_current_count__make_query() / $objs_per_page);
        
        if ( !isset($_REQUEST['page']) || !is_numeric(($_REQUEST['page'])) ) { // проверка полученных данных на существование и является ли целым числом
            $this->page = 1;
        } elseif (isset($_REQUEST['page'])) {
            $this->page = $_REQUEST['page'];
    
            if ($this->page > $this->pages) {
                $this->page = $this->pages;
            } else {
                if (is_numeric($this->page)) $this->page = abs($this->page);
                $this->page = ceil($this->page);
            }
        }

        $this->has_previous = false;
        $this->has_next = false;
        if ($this->pages > 0) {
            if ($this->page != 1) {
                $this->has_previous = true;
                $this->previous_page_number = $this->page - 1;
            }

            if ( $this->page != $this->pages ) {
                $this->has_next = true;
                $this->next_page_number = $this->page + 1;
            }

            $stop_generate = 0;
            $this->pagination_list = [];

            for ($i=1; $i <= $this->pages; $i++) {  // центрируем активированную кнопку
                if ( $stop_generate <= $displayed_count_of_pages && $i > $this->page - ceil($displayed_count_of_pages / 2) ) {
                    if ( $i == $this->page || (!$this->page && $i == 1) ) $this->number = $i;     //присвоение номера активированной ранее кнопке
                    else $this->number = false;
                    $this->pagination_list[] = $i;
                    $stop_generate++;
                    if ($stop_generate >= $displayed_count_of_pages) break;  //заканчиваем создание, если больше определенного значения
                }
            }
        }

        $obj->limit($objs_per_page*($this->page - 1), $objs_per_page);
    }
}