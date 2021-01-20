<?php
/* @var \MyHomeCore\Estates\Estates $myhome_estates */
global $myhome_estates;
/* @var array $myhome_estate_list */
global $myhome_estate_list;
?>
    <div class="mh-grid">
		<?php foreach ( $myhome_estates as $myhome_estate ) : ?>
            <div class="<?php echo esc_attr( $myhome_estate_list['columns'] ); ?>">
                <div class="<?php echo esc_attr( $myhome_estate->get_attribute_classes() . ' ' . $myhome_estate_list['estate_style'] ); ?>">
                    <article class="mh-estate-vertical mh-estate-vertical--list">
						<?php if ( My_Home_Theme()->layout->favorite_enabled() ) : ?>
                            <add-to-favorite
                                    class="myhome-add-to-favorite"
                                    :property-id="<?php echo esc_attr( $myhome_estate->get_ID() ); ?>"
                            ></add-to-favorite>
						<?php endif; ?>

                        <a href="<?php echo esc_url( $myhome_estate->get_link() ); ?>"
                           title="<?php echo esc_attr( $myhome_estate->get_name() ); ?>"
							<?php if ( \MyHomeCore\Estates\Estate::is_new_tab() ) : ?>
                                target="_blank"
							<?php endif; ?>
                        >

                            <div class="mh-thumbnail__inner">
                                <?php
                                $image_url = $myhome_estate->get_data_api()['image_url'];
                                $image_type_check = @exif_imagetype($image_url);//Get image type + check if exists
                                if (!$image_type_check) :
                                    ?>
                                    <a class="mh-popup" href=""
                                       title="الصورة غير متوفرة"
                                    >
                                        <?php echo "<img src='".wp_get_attachment_url( get_option( 'default_image_for_bronken_link' ) )."' ";
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

							<?php if ( $myhome_estate->is_featured() ) : ?>
                                <div class="mh-thumbnail__featured">
                                    <i class="fa fa-star"></i>
                                </div>
							<?php endif; ?>

							<?php
							$myhome_estate_offer_types = $myhome_estate->get_offer_type();
							if ( ! empty( $myhome_estate_offer_types ) ) : ?>
                                <div class="mh-caption">

									<?php foreach ( $myhome_estate->get_offer_type() as $offer_type ) : ?>
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

                            <div class="mh-estate-vertical__text">
                                <div class="mh-estate-vertical__text__inner">
									<?php echo esc_html( $myhome_estate->get_excerpt() ); ?>
                                </div>
                            </div>
                        </a>

                        <div class="mh-estate-vertical__content">
                            <h3 class="mh-estate-vertical__heading">
                                <a href="<?php echo esc_url( $myhome_estate->get_link() ); ?>"
                                   title="<?php echo esc_attr( $myhome_estate->get_name() ); ?>"
									<?php if ( \MyHomeCore\Estates\Estate::is_new_tab() ) : ?>
                                        target="_blank"
									<?php endif; ?>
                                >
									<?php echo esc_html( $myhome_estate->get_name() ); ?>
                                </a>
                            </h3>

                            <address class="mh-estate-vertical__subheading">
								<?php echo esc_html( $myhome_estate->get_address() ); ?>
                            </address>

                            <div class="mh-estate-vertical__primary">

                                <?php if ( $myhome_estate->has_price() ) : ?>
                                    <?php echo esc_html( $myhome_estate->get_price2() .  ' ر.س '); ?>
                                <?php else : ?>
                                    <div class="mh-price__contact">
                                        <?php if($myhome_estate->get_contact_for_price_text() != 'Contact for price')
                                            echo esc_html( $myhome_estate->get_contact_for_price_text() );
                                            else echo 'تواصل للسعر'
                                        ?>
                                    </div>
								<?php endif; ?>
                            </div>
                            <div class="mh-estate-vertical__primary">

                                <?php echo esc_html('رقم اللوحة : ' . $myhome_estate->get_data_api()['board'] ); ?>

                            </div>

                            <div>
                                <div class="mh-estate__list">
									<?php foreach ( $myhome_estate->get_attributes() as $myhome_attribute ) :
										if ( ! $myhome_attribute->show_on_card() ) {
											continue;
										}

										$myhome_attribute_values = $myhome_attribute->get_values();
										if ( ! count( $myhome_attribute_values ) ) {
											continue;
										}
										?>

                                        <div class="mh-estate-vertical__more-info mh-attribute__<?php echo esc_attr( $myhome_attribute->get_slug() ); ?>">
                                            <strong>
												<?php if ( $myhome_attribute->has_icon() ) : ?>
                                                    <i class="<?php echo esc_attr( $myhome_attribute->get_icon() ); ?>"></i>
												<?php else : ?>
													<?php echo esc_html( $myhome_attribute->get_name() ); ?>:
												<?php endif; ?>
                                            </strong>

											<?php foreach ( $myhome_attribute_values as $key => $value ) :
												echo esc_html( ( $key ? ', ' : '' ) . $value->get_name() );
											endforeach; ?>

                                        </div>

									<?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mh-estate-vertical__bottom">
                                <div class="mh-estate-vertical__bottom__inner">

									<?php if ( $myhome_estate_list['show_date'] ) : ?>
                                        <div class="mh-estate-vertical__date">
											<?php echo esc_html( $myhome_estate->get_days_ago() ); ?>
                                        </div>
									<?php endif; ?>

                                    <div class="mh-estate-vertical__buttons-wrapper">
                                        <div class="mh-estate-vertical__buttons">

											<?php if ( My_Home_Theme()->layout->is_compare_enabled() ) : ?>
                                                <div class="mh-estate-vertical__buttons__single">
                                                    <compare-button
                                                            class="myhome-compare-button myhome-compare-button-list"
                                                            :estate="<?php echo esc_attr( json_encode( $myhome_estate->get_data() ) ); ?>"
                                                    ></compare-button>
                                                </div>
											<?php endif; ?>

                                            <div class="mh-estate-vertical__buttons__single">
                                                <a href="<?php echo esc_url( $myhome_estate->get_link() ); ?>"
                                                   title="<?php echo esc_attr( $myhome_estate->get_name() ); ?>"
                                                   class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary-ghost"
													<?php if ( \MyHomeCore\Estates\Estate::is_new_tab() ) : ?>
                                                        target="_blank"
													<?php endif; ?>
                                                >
													<?php esc_html_e( 'التفاصيل', 'myhome' ); ?>
                                                    <span class="mdl-button__icon-right">
                                                    <i class="fa fa-angle-right"></i>
                                                </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
		<?php endforeach; ?>
    </div>

<?php if ( isset( $myhome_estate_list['more_page'] ) && $myhome_estate_list['more_page'] ) : ?>
    <div class="text-center mh-margin-bottom-small mh-margin-top-small">
        <a class="mdl-button mdl-button--lg mdl-js-button mdl-button--raised mdl-button--primary"
           href="<?php echo esc_url( $myhome_estate_list['more_page'] ); ?>"
           title="<?php echo esc_attr( $myhome_estate_list['more_page_text'] ); ?>">
			<?php echo esc_html( $myhome_estate_list['more_page_text'] ); ?>
        </a>
    </div>
<?php endif;