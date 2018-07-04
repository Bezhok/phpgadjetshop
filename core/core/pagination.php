<?php 
namespace core\pagination;

class Pagination
{
    public function __construct($obj, $objs_per_page = 5, $max_count_on_page = 7)
    {
        // обЪекты закрепляются к определнной странице
        $this->pages = ceil($obj->get_current_count__make_query() / $objs_per_page);
        
        if ( !isset($_REQUEST['page']) || !is_numeric(($_REQUEST['page'])) ) { // проверка полуенных данных на существование и является ли целым числом
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

            for ($i=1; $i <= $this->pages; $i++) { 
                if ( $stop_generate <= $max_count_on_page && $i >= $this->page - ceil($max_count_on_page / 2) ) { // центрируем активированную кнопку, всего их 7 
                    if ( $i == $this->page || (!$this->page && $i == 1) ) $this->number = $i;     //присвоение класса активированной ранее кнопке
                    else $this->number = false;
                    $this->pagination_list[] = $i;
                    $stop_generate++;
                    if ($stop_generate >= $max_count_on_page) break;  //заканчиваем создание, если больше 7
                }
            }
        }

        $obj->limit($objs_per_page*($this->page - 1), $objs_per_page);
    }
}