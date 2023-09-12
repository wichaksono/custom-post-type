<?php
namespace NEONBaseCPT;

class PostType {
	protected $post_type_id;
	protected $slug;
	protected $args = [];

	public function __construct( $args ) {
		$this->args = $args;

		$this->post_type_id = $args['id'];
		$slug               = ! empty( $args['rewrite'] ) ? $args['rewrite'] : $args['id'];
		$this->slug         = apply_filters( "{$args['id']}_rewrite_slug", $slug );

		add_action( 'init', [ $this, '_register' ] );
	}

	public function getSlug() {
		return $this->slug;
	}

	public function _register() {
		$args   = $this->args;
		$labels = array(
			'name'                  => $args['title'],
			'singular_name'         => $args['title'],
			'menu_name'             => $args['title'],
			'name_admin_bar'        => $args['title'],
			'add_new'               => 'Add New',
			'add_new_item'          => 'Add New',
			'new_item'              => 'New ' . $args['title'],
			'edit_item'             => 'Edit ' . $args['title'],
			'view_item'             => 'View ' . $args['title'],
			'all_items'             => 'All ' . $args['title'],
			'search_items'          => 'Search ' . $args['title'],
			'parent_item_colon'     => 'Parent ' . $args['title'],
			'not_found'             => 'No ' . $args['title'] . ' found.',
			'not_found_in_trash'    => 'No ' . $args['title'],
			'featured_image'        => $args['title'] . ' Image',
			'set_featured_image'    => 'Set image',
			'remove_featured_image' => 'Remove image',
			'use_featured_image'    => 'Use as image',
			'archives'              => $args['title'] . ' archives',
			'insert_into_item'      => 'Insert into ' . $args['title'],
			'uploaded_to_this_item' => 'Uploaded to this ' . $args['title'],
			'filter_items_list'     => 'Filter ' . $args['title'],
			'items_list_navigation' => $args['title'] . ' list navigation',
			'items_list'            => $args['title'] . ' list',
		);

		$publicly_queryable    = $args['publicly_queryable'] ?? true;
		$disable_in_front_page = apply_filters( "{$args['id']}_publicly_queryable", $publicly_queryable );

		$is_hierarchical = empty( $args['hierarchical'] ) ? false : $args['hierarchical'];
		if ( $is_hierarchical ) {
			$args['supports'][] = 'page-attributes';
		}

		register_post_type( $args['id'],
			array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => $disable_in_front_page,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => $disable_in_front_page,
				'query_var'           => true,
				'rewrite'             => array( 'slug' => $this->slug ),
				'has_archive'         => ! isset( $args['has_archive'] ) ? true : $args['has_archive'],
				'exclude_from_search' => apply_filters( "{$args['id']}_exclude_from_search", ! $disable_in_front_page ),
				'hierarchical'        => $is_hierarchical,
				'menu_position'       => $args['menu_position'],
				'menu_icon'           => $args['menu_icon'],
				'supports'            => $args['supports'],
				'show_in_rest'        => ! empty( $args['show_in_rest'] ) ? $args['show_in_rest'] : false,
			)
		);

		if ( ! empty( $args['group'] ) ) {

			$this->taxonomies( $args['id'], $args['group'] );
		}
	}

	protected function taxonomies( $id, $taxonomy_args ) {
		foreach ( $taxonomy_args as $group ) {
			$labels = array(
				'name'              => $group['title'],
				'singular_name'     => $group['title'],
				'search_items'      => 'Search ' . $group['title'],
				'all_items'         => 'All ' . $group['title'],
				'parent_item'       => 'Parent ' . $group['title'],
				'parent_item_colon' => 'Parent ' . $group['title'] . ':',
				'edit_item'         => 'Edit ' . $group['title'],
				'update_item'       => 'Update ' . $group['title'],
				'add_new_item'      => 'Add New ' . $group['title'],
				'new_item_name'     => 'New ' . $group['title'] . ' Name',
				'menu_name'         => $group['title'],
			);

			$args_tax = array(
				'hierarchical'      => ! empty( $group['hierarchical'] ) ? $group['hierarchical'] : false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => ! empty( $group['show_admin_column'] ) ? $group['show_admin_column'] : false,
				'show_in_rest'      => ! empty( $this->args['show_in_rest'] ) ? $this->args['show_in_rest'] : false,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => empty( $group['rewrite'] ) ? $group['id'] : $group['rewrite'] ),
			);

			register_taxonomy( $group['id'], array( $id ), $args_tax );
		}
	}

	protected function getId() {
		return $this->post_type_id;
	}

}
