<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate_element;

if ( ! empty( get_the_content() ) ) :?>
    <div class="mh-estate__section mh-estate__section--description">

		<?php if ( $myhome_estate_element->has_label() ) : ?>
            <h3 class="mh-estate__section__heading">
                التفاصيل الاضافية
            </h3>
            <div class="mh-estate__section">
                <?php echo esc_html($myhome_estate_element->get_slug() ); ?>
            </div>
        <?php endif; ?>

    </div>
<?php
endif;?>
<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;
if(!empty($myhome_estate->get_gallery())) {
?>

    <div id="mh_rev_gallery_single_wrapper"
     class="rev_slider_wrapper fullwidthbanner-container"
     data-alias="single-estate-gallery">
        <h3 class="mh-estate__section__heading">
            ألبوم الصور
        </h3>
    <div id="mh_rev_gallery_single"
         class="rev_slider myhome-rev_slider fullwidthabanner" data-version="5.3.0">
        <ul class="mh-popup-group">
            <?php foreach ( $myhome_estate->get_gallery() as $myhome_key => $myhome_image ) : ?>
                <li data-index="rs-<?php echo esc_attr( $myhome_key ); ?>"
                    data-transition="<?php echo esc_attr( $myhome_estate->get_gallery_transition() ); ?>"
                    data-slotamount="default"
                    data-hideafterloop="0"
                    data-hideslideonmobile="off"
                    data-easein="default"
                    data-easeout="default"
                    data-masterspeed="500"
                    data-thumb="<?php echo esc_url( wp_get_attachment_image_url( $myhome_image['ID'], 'myhome-standard-xs' ) ); ?>"
                    data-rotate="0"
                    data-fsslotamount="7"
                    data-saveperformance="off"
                    <?php if ( ! $myhome_key ) : ?>
                        data-fstransition="fade"
                        data-fsmasterspeed="300"
                    <?php endif; ?>
                >
                    <img
                            src="<?php echo esc_url( wp_get_attachment_image_url( $myhome_image['image'], 'myhome-standard-l' ) ); ?>"
                        <?php if ( isset( $myhome_image['alt'] ) && ! empty( $myhome_image['alt'] ) ) : ?>
                            alt="<?php echo esc_attr( $myhome_image['alt'] ); ?>"
                        <?php else : ?>
                            alt="<?php the_title_attribute(); ?>"
                        <?php endif; ?>
                            class="rev-slidebg"
                            data-no-retina
                    >
                    <img src="<?php echo esc_url( $myhome_image['image'] ); ?>"
                         alt="<?php echo esc_attr( $myhome_image['alt'] ); ?>">
                    <a href="<?php echo esc_url( $myhome_image['image'] ); ?>" class="mh-popup-group__element">
                        <div
                                class="tp-caption tp-shape tp-shapewrapper  tp-resizeme"
                                data-x="['center','center','center','center']" data-hoffset="['2','2','0','0']"
                                data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']"
                                data-width="full"
                                data-height="full"
                                data-whitespace="normal"
                                data-type="shape"
                                data-basealign="slide"
                                data-responsive_offset="on"
                                data-frames='[{"delay":0,"speed":300,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                data-textAlign="['inherit','inherit','inherit','inherit']"
                                data-paddingtop="[0,0,0,0]"
                                data-paddingright="[0,0,0,0]"
                                data-paddingbottom="[0,0,0,0]"
                                data-paddingleft="[0,0,0,0]"
                        >
                        </div>
                    </a>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php }?>