<?php require_once('base_header.php'); ?> <!-- header -->


    <article class="schema_sorting_block">
        <section class="schema_sorting_block_internal">

            <section class="schema_sorting_block_internal_filter">

                <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="GET">
                    <section class="schema_sorting_block_internal_title">Цена</section>

                        <?php $access['price_form'](); ?><br />

                    <section class="schema_sorting_block_internal_title">Дата выхода на рынок:</section>

                    <ul id="id_years" class="checkbox">
                        <?php $access['years_form'](); ?>
                    </ul>
    
                    <section class="schema_sorting_block_internal_title">Тип товаров:</section>
                    <select name="equipment_type" class="select_list">
                        <?php $access['equipment_type_form'](['All', 'phone', 'tablet', 'name3']); ?>
                    </select>

                    <button class="submit" type="submit">Submit</button>
                </form>

            </section>

            <script type='text/javascript' src="../static/javascript/checkbox.js"></script>
        </section>
    </article>


    <article class="schema_all_products">
        <section class='schema_all_products_box'>

            <?php foreach ($access['products'] as $product): /* вывод товаров */?>

                    <section class="schema_product_main">
                        <section class="schema_product_img">
                            <a href="#">
                                <img src="" alt="<?=$product->name?>"/>
                            </a>
                        </section>
                        <section class="schema_product_description_block">
                            <a href="<?= 'product/'.$product->id ?>">
                                <h2 class="schema_product_title">
                                    <?=$product->name;?>
                                </h2>
                            </a>
                            <section class="schema_price"><?=$product->price;?>руб</section>
                            <section class="schema_product_description">
                                <?=$product->description;?>
                            </section>
                        </section>
                    </section>

            <?php endforeach;?>

        </section>
        <section class="shema_line"></section>
        <section id="schema_all_products_pagination">
            
            <?php foreach ($access['pagination'] as $v) { echo $v ;} ?>
            <section id="pagination_nextNpre_section"></section> <!--for js-->
            <script type='text/javascript' src="../static/javascript/products_pagination.js"></script>
        </section>
    </article>


<?php require_once('base_footer.php'); ?> <!-- footer -->