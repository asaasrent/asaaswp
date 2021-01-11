<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;?>
<div class="mh-estate__main-image">
        <?php

            $image_url = $myhome_estate->get_data_api()['image_url'];
            $image_type_check = @exif_imagetype($image_url);//Get image type + check if exists
            if (strpos($http_response_header[0], "403") || strpos($http_response_header[0], "404") || strpos($http_response_header[0], "302") || strpos($http_response_header[0], "301")) :
        ?>
                <a class="mh-popup" href=""
                   title="الصورة غير متوفرة"
                >
                    <img src="https://dummyimage.com/600x250/7dc9ff/ffffff&text=Sorry+No+image+Available"
                         alt="<?php the_title_attribute(); ?>">
                </a>
        <?php
        else:
            ?>

            <a class="mh-popup" href=""
               title="<?php the_title_attribute(); ?>"
            >
                <img src="<?= $myhome_estate->get_data_api()['image_url']; ?>"
                     alt="<?php the_title_attribute(); ?>">
            </a>
        <?php
            endif;
        ?>

    </div>



