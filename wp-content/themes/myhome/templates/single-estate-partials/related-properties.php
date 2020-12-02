<?php
/* @var \MyHomeCore\Estates\Elements\Related_Properties_Estate_Element $myhome_estate_element */
global $myhome_estate_element;
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_Estate */
global $myhome_estate;
$myhome_related_properties = $myhome_estate->get_related_properties();

if ( count( $myhome_related_properties ) ) :
	?>
    <section class="mh-estate__related">

		<?php if ( $myhome_estate_element->has_label() ) : ?>
            <h3 class="mh-estate__section__heading"><?php echo esc_html( $myhome_estate_element->get_label() ); ?></h3>
		<?php endif; ?>

        <div class="mh-grid">

			<?php foreach ( $myhome_related_properties as $myhome_related_estate ) : ?>

                <div class="mh-grid__1of2">
                    <article
                            class="mh-estate-vertical <?php echo esc_attr( $myhome_related_estate->get_attribute_classes() ); ?>"
                    >
						<?php if ( My_Home_Theme()->layout->favorite_enabled() ) : ?>
                            <add-to-favorite
                                    class="myhome-add-to-favorite"
                                    :property-id="<?php echo esc_attr( $myhome_related_estate->get_ID() ); ?>"
                            ></add-to-favorite>
						<?php endif; ?>

                        <a href="<?php echo esc_url( $myhome_related_estate->get_link() ); ?>"
                           title="<?php echo esc_attr( $myhome_related_estate->get_name() ); ?>"
                           class="mh-thumbnail"
							<?php if ( \MyHomeCore\Estates\Estate::is_new_tab() ) : ?>
                                target="_blank"
							<?php endif; ?>
                        >

							<?php if ( $myhome_related_estate->has_image() ) : ?>
                                <div class="mh-thumbnail__inner">
									<?php
									\MyHomeCore\Common\Image::the_image(
										$myhome_related_estate->get_image_id(),
										'standard',
										$myhome_related_estate->get_name()
									);
									?>
                                </div>
							<?php else: ?>
                                <div class="mh-thumbnail__inner mh-thumbnail__inner--no_image">
                                    <div class="mh-thumbnail__inner--no_image__icon">
                                        <svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512"
                                             xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m343.87 49.688h-172.4l-35.645 75.612h-18.626v-37.806h-91.815v37.806h-25.384v335.39h24.304v-16.203h-8.101v-223.59h124.76v-16.203h-124.76v-63.19h129.9l35.646-75.612h151.65l21.912 48.694 14.776-6.649-26.21-58.247zm-242.88 75.612h-59.41v-21.603h59.409v21.603z"/>
                                            <polygon
                                                    points="408.3 125.3 408.3 141.5 495.8 141.5 495.8 204.69 372.66 204.69 372.66 220.9 495.8 220.9 495.8 444.49 97.215 444.49 97.215 460.69 512 460.69 512 125.3"/>
                                            <rect x="186.33" y="125.3" width="139.34" height="16.203"/>
                                            <rect x="204.15" y="83.173" width="103.7" height="16.203"/>
                                            <rect x="434.23" y="285.7" width="16.203" height="16.203"/>
                                            <rect x="401.82" y="285.7" width="16.203" height="16.203"/>
                                            <rect x="63.19" y="285.7" width="16.203" height="16.203"/>
                                            <rect x="95.595" y="285.7" width="14.582" height="16.203"/>
                                            <path d="m338.07 206.17l-10.482 12.355c24.868 21.098 39.13 51.884 39.13 84.464 0 61.05-49.667 110.72-110.72 110.72-33.823 0-65.355-15.168-86.51-41.612l-12.653 10.12c24.248 30.311 60.39 47.695 99.163 47.695 69.984 0 126.92-56.936 126.92-126.92 0-37.347-16.347-72.637-44.851-96.819z"/>
                                            <path d="m256 176.07c-69.984 0-126.92 56.936-126.92 126.92 0 15.401 2.73 30.453 8.113 44.743l15.162-5.713c-4.693-12.455-7.073-25.587-7.073-39.03 0-61.05 49.667-110.72 110.72-110.72 9.203 0 18.349 1.13 27.184 3.359l3.964-15.711c-10.129-2.555-20.609-3.851-31.147-3.851z"/>
                                            <path d="m220.16 221.4c-5.7 2.507-11.17 5.642-16.259 9.314l9.483 13.137c4.165-3.006 8.64-5.57 13.3-7.621l-6.524-14.83z"/>
                                            <path d="m190.38 242.73c-14.605 15.884-22.94 36.492-23.471 58.032l16.198 0.398c0.434-17.614 7.253-34.47 19.2-47.464l-11.927-10.966z"/>
                                            <path d="m313.22 234.67l-10.41 12.415c16.586 13.906 26.099 34.281 26.099 55.902 0 40.204-32.708 72.911-72.911 72.911-23.123 0-45.11-11.144-58.815-29.81l-13.06 9.589c16.746 22.809 43.615 36.424 71.875 36.424 49.138 0 89.114-39.977 89.114-89.114 0-26.425-11.624-51.326-31.892-68.317z"/>
                                            <path d="m237.65 215.78l3.323 15.859c4.933-1.035 9.989-1.559 15.028-1.559v-16.203c-6.153 0-12.327 0.641-18.351 1.903z"/>
                                            <path d="m291.1 302.99c0 19.358-15.749 35.105-35.105 35.105v16.203c28.291 0 51.308-23.017 51.308-51.308h-16.203z"/>
                                            <rect transform="matrix(.7071 -.7071 .7071 .7071 -106.04 256)" x="-95.348"
                                                  y="247.9" width="702.69" height="16.202"/>
                                            </svg>
                                    </div>
                                </div>
							<?php endif; ?>

							<?php if ( $myhome_related_estate->is_featured() ) : ?>
                                <div class="mh-thumbnail__featured">
                                    <i class="fa fa-star"></i>
                                </div>
							<?php endif; ?>

							<?php
							$myhome_related_estate_offer_types = $myhome_related_estate->get_offer_type();
							if ( ! empty( $myhome_related_estate_offer_types ) ) : ?>
                                <div class="mh-caption">

									<?php foreach ( $myhome_related_estate->get_offer_type() as $offer_type ) : ?>
										<?php if ( $offer_type->get_option( 'has_label' ) ) : ?>
                                            <div
                                                    class="mh-caption__inner mh-label__<?php echo esc_attr( $offer_type->get_slug() ); ?>"
                                                    style="background-color: <?php echo esc_attr( $offer_type->get_option( 'bg_color' ) ); ?>; color: <?php echo esc_attr( $offer_type->get_option( 'color' ) ); ?>"
                                            >
												<?php echo esc_html( $offer_type->get_name() ); ?>
                                            </div>
										<?php endif; ?>
									<?php endforeach; ?>

                                </div>
							<?php endif; ?>

                        </a>

                        <div class="mh-estate-vertical__content">
                            <h3 class="mh-estate-vertical__heading">
                                <a href="<?php echo esc_url( $myhome_related_estate->get_link() ); ?>"
                                   title="<?php echo esc_attr( $myhome_related_estate->get_name() ); ?>"
									<?php if ( \MyHomeCore\Estates\Estate::is_new_tab() ) : ?>
                                        target="_blank"
									<?php endif; ?>
                                >
									<?php echo esc_html( $myhome_related_estate->get_name() ); ?>
                                </a>
                            </h3>

                            <address class="mh-estate-vertical__subheading">
								<?php echo esc_html( $myhome_related_estate->get_address() ); ?>
                            </address>

							<?php if ( $myhome_related_estate->has_price() ) : ?>
                                <div class="mh-estate-vertical__primary">
									<?php foreach ( $myhome_related_estate->get_prices() as $myhome_related_estate_price ) : ?>
                                        <div
											<?php if ( $myhome_related_estate_price->is_range() ) : ?>class="mh-price__range" <?php endif; ?>>
											<?php echo esc_html( $myhome_related_estate_price->get_formatted() ); ?>
                                        </div>
									<?php endforeach;; ?>
                                </div>
							<?php endif; ?>

                        </div>
                    </article>
                </div>

			<?php endforeach; ?>
        </div>
    </section>
<?php
endif;