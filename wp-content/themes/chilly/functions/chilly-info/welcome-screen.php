<?php
/**
 * Welcome Screen Class
 */
class chilly_screen {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'chilly_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'chilly_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'chilly_style_and_scripts' ) );

		/* enqueue script for customizer */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'chilly_scripts_for_customizer' ) );

		/* load welcome screen */
		add_action( 'chilly_info_screen', array( $this, 'chilly_getting_started' ), 	    10 );
		//add_action( 'chilly_info_screen', array( $this, 'chilly_action_required' ), 	    20 );
		add_action( 'chilly_info_screen', array( $this, 'chilly_github' ), 		            40 );
		add_action( 'chilly_info_screen', array( $this, 'chilly_welcome_free_pro' ), 				50 );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_chilly_dismiss_required_action', array( $this, 'chilly_dismiss_required_action_callback') );
		add_action( 'wp_ajax_nopriv_chilly_dismiss_required_action', array($this, 'chilly_dismiss_required_action_callback') );

	}

	public function chilly_register_menu() {
		add_theme_page( 'About Chilly Theme', 'About Chilly Theme', 'activate_plugins', 'chilly-info', array( $this, 'chilly_welcome_screen' ) );
	}

	public function chilly_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'chilly_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @sfunctionse 1.8.2.4
	 */
	public function chilly_admin_notice() {
		?>
			<div class="updated notice is-dismissible spicepress-notice">
				<h1><?php
				$theme_info = wp_get_theme();
				printf( esc_html__('Welcome to %1$s - Version %2$s', 'chilly'), esc_html( $theme_info->Name ), esc_html( $theme_info->Version ) ); ?>
				</h1>
				<p><?php echo sprintf( esc_html__("Welcome! Thank you for choosing SpiceThemes Chilly WordPress theme. To take full advantage of the features this theme has to offer visit our %swelcome page%s.", "chilly"), '<a href="' . esc_url( admin_url( 'themes.php?page=chilly-info' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=chilly-info' ) ); ?>" class="button button-blue-secondary button_chilly" style="text-decoration: none;"><?php _e('Get started with Chilly','chilly'); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 * @sfunctionse  1.8.2.4
	 */
	public function chilly_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_chilly-info' == $hook_suffix ) {
			
			
			wp_enqueue_style( 'chilly-info-css', get_stylesheet_directory_uri() . '/functions/chilly-info/css/bootstrap.css' );
			
			wp_enqueue_style( 'chilly-info-screen-css', get_stylesheet_directory_uri() . '/functions/chilly-info/css/welcome.css' );

			wp_enqueue_script( 'chilly-info-screen-js', get_stylesheet_directory_uri() . '/functions/chilly-info/js/welcome.js', array('jquery') );

			global $chilly_required_actions;

			$nr_actions_required = 0;

			/* get number of required actions */
			if( get_option('chilly_show_required_actions') ):
				$chilly_show_required_actions = get_option('chilly_show_required_actions');
			else:
				$chilly_show_required_actions = array();
			endif;

			if( !empty($chilly_required_actions) ):
				foreach( $chilly_required_actions as $chilly_required_action_value ):
					if(( !isset( $chilly_required_action_value['check'] ) || ( isset( $chilly_required_action_value['check'] ) && ( $chilly_required_action_value['check'] == false ) ) ) && ((isset($chilly_show_required_actions[$chilly_required_action_value['id']]) && ($chilly_show_required_actions[$chilly_required_action_value['id']] == true)) || !isset($chilly_show_required_actions[$chilly_required_action_value['id']]) )) :
						$nr_actions_required++;
					endif;
				endforeach;
			endif;

			wp_localize_script( 'chilly-info-screen-js', 'chillyLiteWelcomeScreenObject', array(
				'nr_actions_required' => $nr_actions_required,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_stylesheet_directory_uri(),
				'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.','chilly' )
			) );
		}
	}

	/**
	 * Load scripts for customizer page
	 * @sfunctionse  1.8.2.4
	 */
	public function chilly_scripts_for_customizer() {

		wp_enqueue_style( 'chilly-info-screen-customizer-css', get_stylesheet_directory_uri() . '/functions/chilly-info/css/welcome_customizer.css' );
		wp_enqueue_script( 'chilly-info-screen-customizer-js', get_stylesheet_directory_uri() . '/functions/chilly-info/js/welcome_customizer.js', array('jquery'), '20120206', true );

		global $chilly_required_actions;

		$nr_actions_required = 0;

		/* get number of required actions */
		if( get_option('chilly_show_required_actions') ):
			$chilly_show_required_actions = get_option('chilly_show_required_actions');
		else:
			$chilly_show_required_actions = array();
		endif;

		if( !empty($chilly_required_actions) ):
			foreach( $chilly_required_actions as $chilly_required_action_value ):
				if(( !isset( $chilly_required_action_value['check'] ) || ( isset( $chilly_required_action_value['check'] ) && ( $chilly_required_action_value['check'] == false ) ) ) && ((isset($chilly_show_required_actions[$chilly_required_action_value['id']]) && ($chilly_show_required_actions[$chilly_required_action_value['id']] == true)) || !isset($chilly_show_required_actions[$chilly_required_action_value['id']]) )) :
					$nr_actions_required++;
				endif;
			endforeach;
		endif;

		wp_localize_script( 'chilly-info-screen-customizer-js', 'chillyLiteWelcomeScreenCustomizerObject', array(
			'nr_actions_required' => $nr_actions_required,
			'aboutpage' => esc_url( admin_url( 'themes.php?page=chilly-info' ) ),
			'customizerpage' => esc_url( admin_url( 'customize.php' ) ),
			'themeinfo' => __('View Theme Info','chilly'),
		) );
	}

	/**
	 * Dismiss required actions
	 * @sfunctionse 1.8.2.4
	 */
	public function chilly_dismiss_required_action_callback() {

		global $chilly_required_actions;

		$chilly_dismiss_id = (isset($_GET['dismiss_id'])) ? $_GET['dismiss_id'] : 0;

		echo $chilly_dismiss_id; /* this is needed and it's the id of the dismissable required action */

		if( !empty($chilly_dismiss_id) ):

			/* if the option exists, update the record for the specified id */
			if( get_option('chilly_show_required_actions') ):

				$chilly_show_required_actions = get_option('chilly_show_required_actions');

				$chilly_show_required_actions[$chilly_dismiss_id] = false;

				update_option( 'chilly_show_required_actions',$chilly_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$chilly_show_required_actions_new = array();

				if( !empty($chilly_required_actions) ):

					foreach( $chilly_required_actions as $chilly_required_action ):

						if( $chilly_required_action['id'] == $chilly_dismiss_id ):
							$chilly_show_required_actions_new[$chilly_required_action['id']] = false;
						else:
							$chilly_show_required_actions_new[$chilly_required_action['id']] = true;
						endif;

					endforeach;

				update_option( 'chilly_show_required_actions', $chilly_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 * Welcome screen content
	 * @sfunctionse 1.8.2.4
	 */
	public function chilly_welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		?>
		<div class="container-fluid">
		<div class="row">
		<div class="col-md-12">
		<ul class="chilly-nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting Started','chilly'); ?></a></li>
			
			<li role="presentation"><a href="#github" aria-controls="github" role="tab" data-toggle="tab"><?php esc_html_e( 'Why Upgrade to PRO?','chilly'); ?></a></li>
			<li role="presentation"><a href="#free_pro" aria-controls="free_pro" role="tab" data-toggle="tab"><?php esc_html_e( 'Free VS PRO','chilly'); ?></a></li>
			
		</ul>
		</div>
		</div>
		</div>

		<div class="chilly-tab-content">

			<?php do_action( 'chilly_info_screen' ); ?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 *
	 */
	public function chilly_getting_started() {
		require_once( get_stylesheet_directory() . '/functions/chilly-info/sections/getting-started.php' );
	}

	/**
	 * Contribute
	 *
	 */
	public function chilly_github() {
		require_once( get_stylesheet_directory() . '/functions/chilly-info/sections/github.php' );
	}


	/**
	 * Free vs PRO
	 * 
	 */
	public function chilly_welcome_free_pro() {
		require_once( get_stylesheet_directory() . '/functions/chilly-info/sections/free_pro.php' );
	}
}

$GLOBALS['chilly_screen'] = new chilly_screen();