<?php

// Prevent direct access to this file
defined( 'ABSPATH' ) or die( 'Nope.' );

/**
 * Register the widget with WordPress
 */
add_action( 'widgets_init', function(){
	register_widget( 'Testimonial_Widget' );
});

class Testimonial_Widget extends WP_Widget {

	/**
	 * Mandatory constructor needs to call the parent constructor with the
	 * following params: id (if false, one will be generated automatically),
	 * the title of the widget (can be translated, of course), and last, params
	 * to further configure the widget.
	 * see https://codex.wordpress.org/Widgets_API for more info
	 */
	public function __construct() {
		parent::__construct(
			false,
			'Testimonials',
			array( 'description' => 'My Testimonials Widget' )
		);
	}

	/**
	 * Renders the widget to the visitors
	 */
	public function widget( $args, $instance ) {

		$header = apply_filters( 'widget_title', empty( $instance['header'] ) ? '' : $instance['header'], $instance, $this->id_base ); ?>

		<h3><?= $header ?></h3>

		<?php foreach ( $instance['testimonials'] as $testimonial ): ?>
			<blockquote>
				<p><?= $testimonial['quote'] ?></p>
				<footer>— <?= $testimonial['author'] ?></footer>
			</blockquote>
		<?php endforeach;

	}

	/**
	 * Sanitizes the widget input before saving the data
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                 = array();
		$instance['header']       = wp_kses_post( $new_instance['header'] );
		$instance['testimonials'] = $new_instance['testimonials'];

		return $instance;

	}

	/**
	 * The most important function, used to show the widget form in the wp-admin
	 */
	public function form( $instance ) {

		$header = empty( $instance['header'] ) ? 'Testimonials' : $instance['header'];

		$testimonials = isset( $instance['testimonials'] )
			? array_values( $instance['testimonials'] )
			: array( array( 'id' => 1, 'quote' => '', 'author' => '', 'image' => '' ) );

		?>

		<!-- segment #1 -->
		<p>
			<label for="<?= $this->get_field_id( 'header' ); ?>">Header</label>
			<input class="widefat" id="<?= $this->get_field_id( 'header' ); ?>" name="<?= $this->get_field_name( 'header' ); ?>" type="text" value="<?= esc_attr( $header ); ?>" />
		</p>

		<!-- segment #2 -->
		<script type="text/template" id="js-testimonial-<?= $this->id; ?>">
			<p>
				<label for="<?= $this->get_field_id( 'testimonials' ); ?>-<%- id %>-quote">Quote:</label>
				<textarea rows="4" class="widefat" id="<?= $this->get_field_id( 'testimonials' ); ?>-<%- id %>-quote" name="<?= $this->get_field_name( 'testimonials' ); ?>[<%- id %>][quote]"><%- quote %></textarea>
			</p>
			<p>
				<label for="<?= $this->get_field_id( 'testimonials' ); ?>-<%- id %>-author">Author:</label>
				<input class="widefat" id="<?= $this->get_field_id( 'testimonials' ); ?>-<%- id %>-author" name="<?= $this->get_field_name( 'testimonials' ); ?>[<%- id %>][author]" type="text" value="<%- author %>" />
			</p>
			<p>
				<input name="<?= $this->get_field_name( 'testimonials' ); ?>[<%- id %>][id]" type="hidden" value="<%- id %>" />
				<a href="#" class="js-remove-testimonial"><span class="dashicons dashicons-dismiss"></span>Remove Testimonial</a>
			</p>
		</script>

		<!-- segment #3 -->
		<div id="js-testimonials-<?= $this->id; ?>">
			<div id="js-testimonials-list" style="padding: 0px 15px; background: #fafafa;"></div>
			<p>
				<a href="#" class="button" id="js-testimonials-add">Add New Testimonial</a>
			</p>
		</div>

		<!-- segment #4 -->
		<script type="text/javascript">
			var testimonialsJSON = <?= json_encode( $testimonials ) ?>;
			myWidgets.repopulateTestimonials( '<?= $this->id; ?>', testimonialsJSON );
		</script>

		<?php
	}

}

