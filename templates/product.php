<?php require_once('base_header.php'); ?> <!-- header -->



    <article>
        <h2 class="product_title"><?=$access['product']->name?></h2>
        <section class="product_main_description">
            <section class="product_md_image"><img src="{{ product.main_image.url }}" alt="<?=$access['product']->name?>" ></section>
            <section>         <b>Производитель:</b> {{ product.manufacturer.name }}</section>
            <section class="product_md_description">
                
                <?=$access['product']->description?>
                    
            </section>
        </section>

        <section class="slider_main_container">
            <section class='slider_button' onclick='previousBlock()'></section>
            <section class="slider_container">
                <ul id='slider'>
<!--                     {% for img in product.images_list %}
                        {% if img %}
                            <li class="block block_product">
                                <section class="gray_block"></section><img src="{{ img.url }}" alt="?=$product->name?>" class="block_image" />
                        {% endif %}
                    {% endfor %} -->
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="../static/images/test/download.jpg" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/d2.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/download.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/download.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/d2.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/download.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/d2.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/download.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/d2.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/download.jpg' %}" />
                    <li class="block block_product">
                        <section class="gray_block"></section><img src="{% static 'images/test/d2.jpg' %}" />

                </ul>
            </section>
            <section class='slider_button slider_button_next' onclick='nextBlock()'></section>
        </section>
        <section id="images_box" ><img id="images_box_img" height="290" src="{% static 'images/test/download.jpg' %}"></section>
        <script type="text/javascript" src="../static/javascript/product_image_slider.js"></script>



<?php require_once('base_footer.php'); ?> <!-- footer -->