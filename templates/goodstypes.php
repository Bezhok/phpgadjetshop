<?php require_once('base_header.php'); ?> <!-- header -->


    <nav id="main_nav_for_types_of_things">
        <ul><!--without </li> for deleting "invisible spacing" between inline-block elements
                                      -->
            {% for type in type_list %}
                <li class="main_nav_thing"><a href="{% url 'products' type.identifier %}">
                    <section>
                        <section class="main_nav_thing_image"><img src="{{ type.image.url }}" alt="{{ type.name }}"/></section>
                        <section class="main_nav_thing_item">
                            <section class="main_nav_thing_title">{{ type.name }}</section>
                            <section class="main_nav_thing_discription">5000 товаров
                                <br />от 200рб</section>
                        </section>
                    </section>
                </a>
            {% endfor %}
        </ul>
    </nav>


<?php require_once('base_footer.php'); ?> <!-- footer -->