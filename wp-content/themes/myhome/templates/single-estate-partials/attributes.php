<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;
?>

	<div class="mh-estate__section mh-estate__section--attributes">
        <h3 class="mh-estate__section__heading">
            التفاصيل الأساسية
        </h3>

        <div class="mh-estate__list">
            <ul class="mh-estate__list__inner">
                    <li class="mh-estate__list__element">
                        <strong>
                            نوع العقار :
						</strong>
                        <?php echo esc_html( $myhome_estate->get_data_api()['property_type'] ); ?>
                    </li>
                    <li class="mh-estate__list__element">
                        <strong>
                            نوع العرض :
						</strong>
                        <?php echo esc_html( $myhome_estate->get_data_api()['offer_type_ar'] ); ?>
                    </li>
                    <li class="mh-estate__list__element">
                        <strong>
                           السعر :
						</strong>
                        <?php echo esc_html( $myhome_estate->get_data_api()['price'] ); echo 'ر.س'?>
                    </li>
                    <li class="mh-estate__list__element">
                        <strong>
                            المدينة :
                        </strong>
                        <?php echo esc_html( $myhome_estate->get_data_api()['city_name'] ); ?>
                    </li>
                    <li class="mh-estate__list__element">
                        <strong>
                            رقم اللوحة :
                        </strong>
                        <?php if($myhome_estate->get_data_api()['board']!= '') echo esc_html( $myhome_estate->get_data_api()['board'] ); ?>
                    </li>
                    <li class="mh-estate__list__element">
                        <strong>
                            الحي :
                        </strong>
                        <?php echo esc_html( $myhome_estate->get_data_api()['neighborhood_name'] ); ?>
                    </li>

				<?php if ( $myhome_estate->has_additional_features() ) : ?>
					<?php foreach ( $myhome_estate->get_additional_features() as $myhome_additional_feature ) : ?>
						<li class="mh-estate__list__element">
                            <strong>
                                <?php echo esc_html( $myhome_additional_feature['name'] ); ?><?php if ( ! empty( $myhome_additional_feature['value'] ) ) : ?>:<?php endif; ?>
                            </strong>
							<?php if ( ! empty( $myhome_additional_feature['name'] ) ) : ?>

							<?php endif; ?>

							<?php if ( ! empty( $myhome_additional_feature['value'] ) ) : ?>
								<?php echo esc_html( $myhome_additional_feature['value'] ); ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>

<?php
foreach ( $myhome_estate->get_attributes() as $myhome_attr ) :
	if ( ! $myhome_attr->has_values() || ! $myhome_attr->new_box() ) :
		continue;
	endif;

	?>
	<div class="mh-estate__section">

		<h3 class="mh-estate__section__heading">
			<?php echo esc_html( $myhome_attr->get_name() ); ?>
		</h3>


		<div class="mh-estate__list">
			<ul class="mh-estate__list__inner">

				<?php foreach ( $myhome_attr->get_values() as $myhome_attr_key => $myhome_attr_value ) : ?>

					<li class="mh-estate__list__element mh-estate__list__element--dot">

						<?php if ( $myhome_attr->has_archive() ) : ?>

							<a href="<?php echo esc_url( $myhome_attr_value->get_link() ); ?>"
							   title="<?php echo esc_attr( $myhome_attr_value->get_name() ) ?>">
								<?php echo esc_html( $myhome_attr_value->get_name() ); ?>
							</a>

						<?php else : ?>

							<?php echo esc_html( $myhome_attr_value->get_name() ); ?>

						<?php endif; ?>


					</li>

				<?php endforeach;; ?>

			</ul>
		</div>
	</div>

<?php
endforeach;
