<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;?>
<div class="mh-estate__main-image">
        <?php

            $image_url = $myhome_estate->get_data_api()['image_url'];
            $image_type_check = @exif_imagetype($image_url);//Get image type + check if exists
            if (!$image_type_check) :
        ?>
                <a class="mh-popup" href=""
                   title="الصورة غير متوفرة"
                >
                    <?php echo "<img src='".wp_get_attachment_url( get_option( 'default_image_for_bronken_link' ) )."' />";
                    ?>
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



