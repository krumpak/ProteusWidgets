<?php
/**
 * Icon Box Widget
 *
 * @package ProteusWidgets
 * @since 1.0.0
 */


if ( ! class_exists( 'PW_Icon_Box' ) ) {
	class PW_Icon_Box extends PW_Widget {

		private $fields;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {

			// Overwrite the widget variables of the parent class
			$this->widget_id_base     = 'icon_box';
			$this->widget_name        = esc_html__( 'Icon Box', 'proteuswidgets' );
			$this->widget_description = esc_html__( 'Linkable block with title, text and font awesome icon.', 'proteuswidgets' );
			$this->widget_class       = 'widget-icon-box';

			parent::__construct();

			// Get the settings for the icon box widgets
			$this->fields = apply_filters( 'pw/icon_box_widget', array(
				'featured_setting' => false,
			) );
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			// Prepare data for template
			$instance['target']   = ! empty ( $instance['new_tab'] ) ? '_blank' : '_self';

			// widget-icon-box template rendering
			echo $this->template_engine->render_template( apply_filters( 'pw/widget_icon_box_view', 'widget-icon-box' ), array(
				'args'     => $args,
				'instance' => $instance,
			));
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			$instance['title']    = wp_kses_post( $new_instance['title'] );
			$instance['text']     = wp_kses_post( $new_instance['text'] );
			$instance['btn_link'] = esc_url_raw( $new_instance['btn_link'] );
			$instance['icon']     = sanitize_text_field( $new_instance['icon'] );
			$instance['new_tab']  = ! empty ( $new_instance['new_tab'] ) ? sanitize_key( $new_instance['new_tab'] ) : '';

			if ( $this->fields['featured_setting'] ) {
				$instance['featured'] = ! empty ( $new_instance['featured'] ) ? sanitize_key( $new_instance['featured'] ) : '';
			}

			return $instance;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			$title    = empty( $instance['title'] ) ? '' : $instance['title'];
			$text     = empty( $instance['text'] ) ? '' : $instance['text'];
			$btn_link = empty( $instance['btn_link'] ) ? '' : $instance['btn_link'];
			$icon     = empty( $instance['icon'] ) ? '' : $instance['icon'];
			$new_tab  = empty( $instance['new_tab'] ) ? '' : $instance['new_tab'];

			if ( $this->fields['featured_setting'] ) {
				$instance['featured'] = empty ( $instance['featured'] ) ? '' : $instance['featured'];
			}

			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'proteuswidgets' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'proteuswidgets' ); ?></label> <br />
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_link' ) ); ?>"><?php esc_html_e( 'Link:', 'proteuswidgets' ); ?></label> <br />
				<small><?php esc_html_e( 'URL to any page, optional.', 'proteuswidgets' ); ?></small> <br>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_link' ) ); ?>" type="text" value="<?php echo esc_url( $btn_link ); ?>" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $new_tab, 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'new_tab' ) ); ?>" value="on" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'new_tab' ) ); ?>"><?php esc_html_e( 'Open link in new tab', 'proteuswidgets' ); ?></label>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'Icon:', 'proteuswidgets' ); ?></label> <br />
				<small><?php echo wp_kses_post( apply_filters( 'pw/icons_input_field_notice', sprintf( esc_html__( 'Click on the icon below or manually select from the %s website.', 'proteuswidgets' ), '<a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a>' ) ) ); ?></small>
				<input id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>" type="text" value="<?php echo esc_attr( $icon ); ?>" class="widefat  js-icon-input" /> <br><br>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-home"><i class="fa fa-lg fa-home"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-phone"><i class="fa fa-lg fa-phone"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-clock-o"><i class="fa fa-lg fa-clock-o"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-beer"><i class="fa fa-lg fa-beer"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-camera-retro"><i class="fa fa-lg fa-camera-retro"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-check-circle-o"><i class="fa fa-lg fa-check-circle-o"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-cog"><i class="fa fa-lg fa-cog"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-cogs"><i class="fa fa-lg fa-cogs"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-comments-o"><i class="fa fa-lg fa-comments-o"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-compass"><i class="fa fa-lg fa-compass"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-dashboard"><i class="fa fa-lg fa-dashboard"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-download"><i class="fa fa-lg fa-download"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-exclamation-circle"><i class="fa fa-lg fa-exclamation-circle"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-male"><i class="fa fa-lg fa-male"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-female"><i class="fa fa-lg fa-female"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-fire"><i class="fa fa-lg fa-fire"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-flag"><i class="fa fa-lg fa-flag"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-folder-open-o"><i class="fa fa-lg fa-folder-open-o"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-heart"><i class="fa fa-lg fa-heart"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-inbox"><i class="fa fa-lg fa-inbox"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-info-circle"><i class="fa fa-lg fa-info-circle"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-key"><i class="fa fa-lg fa-key"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-laptop"><i class="fa fa-lg fa-laptop"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-leaf"><i class="fa fa-lg fa-leaf"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-map-marker"><i class="fa fa-lg fa-map-marker"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-money"><i class="fa fa-lg fa-money"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-plus-circle"><i class="fa fa-lg fa-plus-circle"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-print"><i class="fa fa-lg fa-print"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-quote-right"><i class="fa fa-lg fa-quote-right"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-quote-left"><i class="fa fa-lg fa-quote-left"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-shopping-cart"><i class="fa fa-lg fa-shopping-cart"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-sitemap"><i class="fa fa-lg fa-sitemap"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-star-o"><i class="fa fa-lg fa-star-o"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-suitcase"><i class="fa fa-lg fa-suitcase"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-thumbs-up"><i class="fa fa-lg fa-thumbs-up"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-tint"><i class="fa fa-lg fa-tint"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-truck"><i class="fa fa-lg fa-truck"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-users"><i class="fa fa-lg fa-users"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-warning"><i class="fa fa-lg fa-warning"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-wrench"><i class="fa fa-lg fa-wrench"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-chevron-right"><i class="fa fa-lg fa-chevron-right"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-chevron-circle-right"><i class="fa fa-lg fa-chevron-circle-right"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-chevron-down"><i class="fa fa-lg fa-chevron-down"></i></a>
				<a class="js-selectable-icon  icon-widget" href="#" data-iconname="fa-chevron-circle-down"><i class="fa fa-lg fa-chevron-circle-down"></i></a>
			</p>

			<?php if ( $this->fields['featured_setting'] ) : ?>
				<p>
					<input class="checkbox" type="checkbox" <?php checked( $instance['featured'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" value="on" />
					<label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>"><?php esc_html_e( 'Highlight this widget.', 'proteuswidgets' ); ?></label>
				</p>
			<?php endif; ?>

			<?php
		}

	}
}