<?php

# register new post type
class RecipeMenu extends \NEONBaseCPT\PostType {
  
	public function __construct() {
		parent::__construct( [
      # post type id. MUST unique
			'id'            => 'resep',
      
      # title post type
			'title'         => 'Resep',

      # 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'
			'supports'      => [ 'title', 'thumbnail' ],
      
      # @see https://developer.wordpress.org/reference/functions/register_post_type/#menu_position             
			'menu_position' => 25,

      # @see https://developer.wordpress.org/resource/dashicons/
			'menu_icon'     => 'dashicons-food',

      # true: gutenberg editor, false: classic editor
			'show_in_rest'  => false,

      # enable mode archive for custom post type ex: https://domain.com/resep
			'has_archive'   => false,

      # adding taxonomy like category or tags
			'group'         => [
				[
					'id'                => 'resep-category',
					'title'             => 'Category',
                        
          # show_admin_column: true show taxonomy in table list. default: false
					'show_admin_column' => true,
                        
          # hierarchical: true show like category, false like tags. default: false
					'hierarchical'      => true, 
				],
				[
					'id'                => 'hashtag',
					'title'             => 'Hashtags',
					'show_admin_column' => true,
				]
			]
		] );

		add_filter( 'manage_' . $this->post_type_id . '_posts_columns', [ $this, 'setColumns' ] );
		add_action( 'manage_' . $this->post_type_id . '_posts_custom_column', [ $this, 'setColumn' ], 10, 2 );

	}

	# adding custom label on table lists
	public function setColumns( $columns ) {
//		$columns['label_id'] = 'Label Name';
		return $columns;
	}

	# adding value custom label
	public function setColumn( $column, $post_id ) {
//		switch ($column) {
//			case 'label_id';
//				echo 'Label Value';
//			break;
//		}
	}

}

# run class to create new custom post type 
new self();
