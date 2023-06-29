<?php
/**
 * BlockArt Image Import Controller.
 *
 * @package BlockArt
 */


namespace BlockArt\RestApi\Controllers;

class ImageImportController extends \WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @var string The namespace of this controller's route.
	 */
	protected $namespace = 'blockart/v1';

	/**
	 * The base of this controller's route.
	 *
	 * @var string The base of this controller's route.
	 */
	protected $rest_base = 'image-import';

	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'import_image' ),
					'permission_callback' => array( $this, 'import_image_permissions_check' ),
					'args'                => array(
						'url' => array(
							'sanitize_callback' => 'esc_url_raw',
							'required'          => true,
						),
					),
				),
			)
		);
	}

	/**
	 * Import image from url.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function import_image( \WP_REST_Request $request ) {
		$url = $request->get_param( 'url' );
		global $wpdb;

		$response = array();

		// Check if image already exists.
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_value FROM $wpdb->postmeta WHERE ( meta_key = '_source_url' AND meta_value = %s ) OR ( meta_key = '_blockart_image_hash' AND meta_value = %s )",
				$url,
				sha1( $url )
			)
		);

		if ( $results ) {
			$id             = $results[0]->post_id ?? 0;
			$attachment_url = wp_get_attachment_url( $id );
			$attachment_url = empty( $attachment_url ) ? $url : $attachment_url;

			return new \WP_REST_Response(
				array(
					'sourceURL' => $url,
					'url'       => $attachment_url,
				),
				200
			);
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		// Handle image upload.
		$src = media_sideload_image( $url, 0, '', 'src' );

		if ( is_wp_error( $src ) ) {
			return $src;
		}

		return new \WP_REST_Response(
			array(
				'sourceURL' => $url,
				'url'       => $src,
			),
			200
		);
	}

	/**
	 * Check if a given request has access to get items.
	 *
	 * @return true|\WP_Error
	 */
	public function import_image_permissions_check() {
		if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( 'upload_files' ) ) {
			return new \WP_Error(
				'rest_forbidden',
				esc_html__( 'You are not allowed to access this resource.', 'blockart-blocks' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}
}
