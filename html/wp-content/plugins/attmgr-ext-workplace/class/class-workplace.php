<?php
/** 
 *	ATTMGR Staff Calendar Module
 */
ATTMGR_EXT_Workplace::load();

class ATTMGR_EXT_Workplace {
	// Settings
	const TEXTDOMAIN              = 'attmgr-ext-workplace';
	const PLUGIN_ID               = 'attmgr-ext-workplace';
	const PLUGIN_FILE             = 'attmgr-ext-workplace.php';
	const PLUGIN_VERSION          = '1.0.4';
	const DB_VERSION              = '1.0.0';
	const FEED_URL                = 'http://attmgr.com/extensions/category/updates/workplace/feed';

	/** 
	 *	Load
	 */
	public static function load() {
		$mypluginurl  = dirname( plugin_dir_url( __FILE__ ) ).'/';
		$mypluginpath = dirname( plugin_dir_path( __FILE__ ) ).'/';
		$mypluginfile = $mypluginpath.ATTMGR_EXT_Workplace::PLUGIN_FILE;

		load_plugin_textdomain( ATTMGR_EXT_Workplace::TEXTDOMAIN, false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages');
		register_activation_hook( $mypluginfile, array( 'ATTMGR_EXT_Workplace', 'activation' ) );
		register_deactivation_hook( $mypluginfile, array( 'ATTMGR_EXT_Workplace', 'deactivation' ) );
		register_uninstall_hook( $mypluginfile, array( 'ATTMGR_EXT_Workplace', 'uninstall' ) );

		add_action('init', array( 'ATTMGR_EXT_Workplace', 'init' ), 99 );
	}

	/**
	 *	Initialize
	 */
	public static function init() {
		global $attmgr;

		add_action( 'admin_menu', array( 'ATTMGR_EXT_Workplace', 'add_menu' ), 99 );
		add_action( ATTMGR_EXT_Workplace::PLUGIN_ID.'_update_info', array( 'ATTMGR_EXT_Workplace', 'update_info') );

		// Renew: Updation
		remove_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_Form', 'update_by_staff' ), 99 );
		remove_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_Form', 'update_by_admin' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_EXT_Workplace', 'update_by_staff' ), 99 );
		add_filter( ATTMGR::PLUGIN_ID.'_action', array( 'ATTMGR_EXT_Workplace', 'update_by_admin' ), 99 );

		// Renew: Scheduler
		remove_shortcode( ATTMGR::PLUGIN_ID.'_staff_scheduler' );
		remove_shortcode( ATTMGR::PLUGIN_ID.'_admin_scheduler' );
		add_shortcode( ATTMGR::PLUGIN_ID.'_staff_scheduler', array( 'ATTMGR_EXT_Workplace', 'staff_scheduler' ) );
		add_shortcode( ATTMGR::PLUGIN_ID.'_admin_scheduler', array( 'ATTMGR_EXT_Workplace', 'admin_scheduler' ) );

		// Renew: Weekly
		remove_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_attendance', array( 'ATTMGR_Shortcode', 'weekly_attendance' ), 99, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_attendance', array( 'ATTMGR_EXT_Workplace', 'weekly_attendance' ), 99, 2 );

		// Renew: Weekly All
		remove_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all_attendance', array( 'ATTMGR_Shortcode', 'weekly_all_attendance' ), 99, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_weekly_all_attendance', array( 'ATTMGR_EXT_Workplace', 'weekly_all_attendance' ), 99, 2 );

		// Renew: Daily
		remove_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily_values', array( 'ATTMGR_Shortcode', 'daily_values' ), 99, 2 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily_values', array( 'ATTMGR_EXT_Workplace', 'daily_values' ), 99, 2 );

		add_shortcode( ATTMGR::PLUGIN_ID.'_today_place', array( 'ATTMGR_EXT_Workplace', 'today_place' ) );

		// Renew: Daily
		remove_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily', array( 'ATTMGR_Shortcode', 'daily_schedule' ), 99, 3 );
		add_filter( ATTMGR::PLUGIN_ID.'_shortcode_daily', array( 'ATTMGR_EXT_Workplace', 'daily_schedule' ), 99, 3 );
	}

	/** 
	 *	Alter table
	 */
	public static function alter_table( $action = 'add' ) {
		global $wpdb;

		require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
		$table = '';
		$table = apply_filters( 'attmgr_schedule_table_name', $table );
		$describe = $wpdb->get_var( "DESCRIBE $table `workplace`" );

		// Add column
		if ( 'add' == $action ) {
			if ( $table == $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) &&
				empty( $describe ) ) {
				$sql = <<<EOD
ALTER TABLE $table 
ADD `workplace` VARCHAR( 256 ) NOT NULL COMMENT 'Workplace';
EOD;
				$wpdb->query( $sql );
			}
		}
		// Drop column
		elseif ( 'drop' == $action ) {
			if ( $table == $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) &&
				! empty( $describe ) ) {
				$sql = <<<EOD
ALTER TABLE $table 
DROP `workplace`;
EOD;
				$wpdb->query( $sql );
			}
		}
		return;
	}

	/** 
	 *	Activation
	 */
	public static function activation() {
		load_plugin_textdomain( ATTMGR_EXT_Workplace::TEXTDOMAIN, false, dirname( dirname( plugin_basename(__FILE__) ) ).'/languages');
		ATTMGR_EXT_Workplace::alter_table('add');

		$option = get_option( ATTMGR_EXT_Workplace::PLUGIN_ID );
		if ( empty( $option ) ) {
			$option = ATTMGR_EXT_Workplace::default_option();
			update_option( ATTMGR_EXT_Workplace::PLUGIN_ID, $option );
		}
	}

	/** 
	 *	Deactivation
	 */
	public static function deactivation() {

	}

	/** 
	 *	Uninstall
	 */
	public static function uninstall() {
		ATTMGR_EXT_Workplace::alter_table('drop');
		delete_option( ATTMGR_EXT_Workplace::PLUGIN_ID );
	}

	/** 
	 *	Set default plugins options
	 */
	public static function default_option(){
		$default_option = array(
			'module_ver'    => ATTMGR_EXT_Workplace::PLUGIN_VERSION,
			'db_ver'        => ATTMGR_EXT_Workplace::DB_VERSION,
			'workplace'     => __('Shop', ATTMGR_EXT_Workplace::TEXTDOMAIN )." A\n"
							  .__('Shop', ATTMGR_EXT_Workplace::TEXTDOMAIN )." B\n"
							  .__('Shop', ATTMGR_EXT_Workplace::TEXTDOMAIN )." C\n",
		);
		return $default_option;
	}

	/**
	 *	Get Workplace list
	 */
	public static function get_workplace_list(){
		$options = get_option( ATTMGR_EXT_Workplace::PLUGIN_ID );
		$workplace = array();
		if ( ! empty( $options['workplace'] ) ) {
			$workplace = explode( "\n", trim( $options['workplace'] ) );
			$workplace = array_map( 'trim', $workplace );
			$workplace = array_filter( $workplace, 'strlen' );
			$workplace = array_values( $workplace );
		}
		return $workplace;
	}

	/**
	 *	(function) Make select tag 
	 */
	public static function select_workplace( $atts ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'current'  => null,
					'name'     => null,
					'class'    => array(),
				),
				$atts
			)
		);
		$subject = <<<EOD
<select class="%CLASS%" name="%NAME%">
%OPTIONS%</select>
EOD;
		$options = '<option value=""></options>'."\n";
		$workplace_list = ATTMGR_EXT_Workplace::get_workplace_list();
		if ( ! empty( $workplace_list ) ) {
			foreach ( $workplace_list as $wp ) {
				$selected = ( $current == $wp ) ? 'selected' : '';
				$options .= sprintf( '<option value="%s" %s >%s</options>'."\n", $wp, $selected, $wp );
			}
		}
		$search = array(
			'%NAME%',
			'%CLASS%',
			'%OPTIONS%'
		);
		$replace = array(
			$name,
			( !empty( $class ) ) ? implode( ' ', $class ) : '',
			$options
		);
		$html = str_replace( $search, $replace, "\n".$subject );
		return $html;
	}

	/**
	 *	Scheduler for staff
	 */
	public static function update_by_staff( $result ) {
		global $attmgr, $wpdb;
		
		if ( ATTMGR::PLUGIN_ID.'_update_by_staff' != $_POST['action'] ) {
			return $result;
		}

		$error = '';
		if ( empty( $_POST['onetimetoken'] ) || ! wp_verify_nonce( $_POST['onetimetoken'], ATTMGR::PLUGIN_ID ) ) {
			$error = 'NONCE_ERROR';
		} else {
			$staff_id = $attmgr->user['operator']->data['ID'];
			$values = array();
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_post'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_post'] as $date => $value ) {
					$workplace = $value['workplace'];
					$starttime = $value['starttime'];
					$endtime = $value['endtime'];
					if ( empty( $workplace ) ) {
						$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL, NULL )", array( $staff_id, $date ) );
					} else {
						if ( empty( $starttime ) && empty( $endtime ) ) {
							$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL, %s )", array( $staff_id, $date, $workplace ) );
						} else {
							$values[] = $wpdb->prepare( "( %d, %s, %s, %s, %s )", array( $staff_id, $date, $starttime, $endtime, $workplace ) );
						}
					}
				}
			}
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "INSERT INTO $table "
					."( `staff_id`, `date`, `starttime`, `endtime`, `workplace` ) "
					."VALUES "
					.implode( ',', $values )." "
					."ON DUPLICATE KEY UPDATE "
					."starttime = VALUES( starttime ), "
					."endtime = VALUES( endtime ), "
					."workplace = VALUES( workplace ) ";
			$ret = $wpdb->query( $query );
			$ret = $wpdb->query( "DELETE FROM $table WHERE starttime IS NULL AND endtime IS NULL " );

			// OFF
			$del_where = array();
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_off'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_off'] as $date => $value ) {
					$del_where[] = sprintf( "'%s'", $date );
				}
				$ret = $wpdb->query( "DELETE FROM $table WHERE `staff_id`=$staff_id AND `date` IN (".implode( ',', $del_where )." )" );
			}
		}
		$url = $_REQUEST['_wp_http_referer'];
		// エラーあり
		if ( $error ) {
			$url = add_query_arg( array( 'error' => $error ), $url );
		}
		wp_redirect( $url );
		exit;
	}
	
	/**
	 *	Scheduler for admin
	 */
	public static function update_by_admin( $result ) {
		global $attmgr, $wpdb;
		
		if ( ATTMGR::PLUGIN_ID.'_update_by_admin' != $_POST['action'] ) {
			return $result;
		}
		$error = '';
		if ( empty( $_POST['onetimetoken'] ) || ! wp_verify_nonce( $_POST['onetimetoken'], ATTMGR::PLUGIN_ID ) ) {
			$error = 'NONCE_ERROR';
		} else {
			$table = '';
			$table = apply_filters( 'attmgr_schedule_table_name', $table );
			$query = "INSERT INTO $table "
					."( `staff_id`, `date`, `starttime`, `endtime`, `workplace` ) "
					."VALUES "
					."%VALUES% "
					."ON DUPLICATE KEY UPDATE "
					."starttime = VALUES( starttime ), "
					."endtime = VALUES( endtime ), "
					."workplace = VALUES( workplace ) ";
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_post'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_post'] as $staff_id => $data ) {
					$values = array();
					// Update
					foreach ( $data as $date => $value ) {
						$workplace = $value['workplace'];
						$starttime = $value['starttime'];
						$endtime = $value['endtime'];
						if ( empty( $workplace ) ) {
							$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL, NULL )", array( $staff_id, $date ) );
						} else {
							if ( empty( $starttime ) && empty( $endtime ) ) {
								$values[] = $wpdb->prepare( "( %d, %s, NULL, NULL, %s )", array( $staff_id, $date, $workplace ) );
							} else {
								$values[] = $wpdb->prepare( "( %d, %s, %s, %s, %s )", array( $staff_id, $date, $starttime, $endtime, $workplace ) );
							}
						}
					}
					$sql = str_replace( '%VALUES%', implode( ',', $values ), $query ); 
					$ret = $wpdb->query( $sql );
				}
				$ret = $wpdb->query( "DELETE FROM $table WHERE starttime IS NULL AND endtime IS NULL " );
			}
			// OFF
			if ( ! empty( $_POST[ATTMGR::PLUGIN_ID.'_off'] ) ) {
				foreach ( $_POST[ATTMGR::PLUGIN_ID.'_off'] as $staff_id => $data ) {
					$del_where = array();
					foreach ( $data as $date => $value ) {
						$del_where[] = sprintf( "'%s'", $date );
					}
					$ret = $wpdb->query( "DELETE FROM $table WHERE `staff_id`=$staff_id AND `date` IN (".implode( ',', $del_where )." )" );
				}
			}
		}
		$url = $_REQUEST['_wp_http_referer'];
		// エラーあり
		if ( $error ) {
			$url = add_query_arg( array( 'error' => $error ), $url );
		}
		wp_redirect( $url );
		exit;
	}

	/**
	 *	Scheduler for staff
	 */
	public static function staff_scheduler( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'name_key' => 'display_name',
				),
				$atts
			)
		);
		ob_start();
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'There are no staff.', ATTMGR::TEXTDOMAIN ) );
		} else {
			if ( $attmgr->user['operator']->is_staff() ) {
				$staff_id = $attmgr->user['operator']->data['ID'];
				$startdate = $attmgr->page['startdate'];
				list( $y, $m, $d ) = explode( '-', $startdate );
				$m = intval( $m );
				$d = intval( $d );
				$starttime = mktime( 0, 0, 0, $m, $d, $y );

				$term = $attmgr->option['general']['editable_term'];
				$endtime = mktime( 0, 0, 0, $m, $d + $term, $y );
				$enddate = date( 'Y-m-d', $endtime );

				$table = '';
				$table = apply_filters( 'attmgr_schedule_table_name', $table );
				$query = "SELECT * FROM $table "
						."WHERE staff_id = %d "
						."AND ( date>=%s AND date<= %s ) ";
				$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
				$schedule = array();
				if ( !empty( $records ) ) {
					foreach ( $records as $r ) {
						$schedule[ $r['date'] ] = $r;
						$schedule[ $r['date'] ]['starttime'] = substr( $schedule[ $r['date'] ]['starttime'], 0, 5 );
						$schedule[ $r['date'] ]['endtime'] = substr( $schedule[ $r['date'] ]['endtime'], 0, 5 );
						$schedule[ $r['date'] ]['workplace'] = $schedule[ $r['date'] ]['workplace'];
					}
				}
				// Portrait
				$portrait = null;
				$portrait = ATTMGR_Function::get_portrait( $portrait, $attmgr->user['operator'] );
				$name = $attmgr->user['operator']->data[ $name_key ];

				// Profile
				$profile = sprintf( '<h3 class="name">%s</h3>', $name );

				// Return url
				$url = '';

				$format = <<<EOD
%NAVI%
<form id="%FORM_ID%" method="post">
<div class="portrait">%PORTRAIT%</div>
<div class="profile">%PROFILE%</div>
<table class="%CLASS%">
<tr><th class="date">%DATE_LABEL%</th><th class="time">%TIME_LABEL%</th></tr>
%SCHEDULE%
</table>
%NONCE%
<input type="hidden" name="returnurl" value="%RETURN_URL%" />
<input type="hidden" name="action" value="%ACTION%" />
<input type="submit" name="submit" value="%SUBMIT%" />
</form>
%MESSAGE%
EOD;
				$param = array(
					'start'    => $attmgr->option['general']['starttime'],
					'end'      => $attmgr->option['general']['endtime'],
					'interval' => $attmgr->option['general']['interval'],
					'class'    => array(),
				);

				$line = '';
				for ( $i = 0; $i < 7; $i++ ) {
					$t = $starttime + 60*60*24*$i;
					$d = date( 'Y-m-d', $t );
					$w = date( 'w', $t );
					$dow = ATTMGR_Calendar::dow( $w );

					$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['starttime'] : '';
					$param['name'] = ATTMGR::PLUGIN_ID.'_post['.$d.'][starttime]';
					$st = ATTMGR_Form::select_time( $param );

					$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['endtime'] : '';
					$param['name'] = ATTMGR::PLUGIN_ID.'_post['.$d.'][endtime]';
					$et = ATTMGR_Form::select_time( $param );

					$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['workplace'] : '';
					$param['name'] = ATTMGR::PLUGIN_ID.'_post['.$d.'][workplace]';
					$wp = ATTMGR_EXT_Workplace::select_workplace( $param );

					$off = sprintf( '<label><input type="checkbox" name="%s_off[%s]" value="1" />%s</label>', ATTMGR::PLUGIN_ID, $d, __( 'DEL', ATTMGR::TEXTDOMAIN ) );
					$date = '';
					$date = sprintf( '%s(%s)', 
						apply_filters( 'attmgr_date_format', $date, $t ),
						ATTMGR_Calendar::dow( $w )
					);
					$line .= sprintf( '<tr><td class="date">%s</td><td>%s %s~%s %s</td></tr>'."\n", $date, $off, $st, $et, $wp );
				}
				$search = array(
					'%NAVI%',
					'%FORM_ID%',
					'%CLASS%',
					'%PORTRAIT%',
					'%PROFILE%',
					'%DATE_LABEL%',
					'%OFF_LABEL%',
					'%TIME_LABEL%',
					'%SCHEDULE%',
					'%NONCE%',
					'%RETURN_URL%',
					'%ACTION%',
					'%SUBMIT%',
					'%MESSAGE%',
				);
				$replace = array(
					ATTMGR_Calendar::show_navi_weekly( $startdate ),
					ATTMGR::PLUGIN_ID.'_staff_scheduler',
					ATTMGR::PLUGIN_ID.'_staff_scheduler',
					$portrait,
					$profile,
					__( 'Date', ATTMGR::TEXTDOMAIN ),
					__( 'Off', ATTMGR::TEXTDOMAIN ),
					__( 'Time', ATTMGR::TEXTDOMAIN ),
					$line,
					wp_nonce_field( ATTMGR::PLUGIN_ID, 'onetimetoken', true, false ),
					$url,
					ATTMGR::PLUGIN_ID.'_update_by_staff',
					__( 'Update', ATTMGR::TEXTDOMAIN ),
					'',
				);
				$subject = str_replace( $search, $replace, $format );
				echo $subject;
			} else {
				$error_msg = __( 'Permission denied.', ATTMGR::TEXTDOMAIN ).'<br>';
				$error_msg .= __( 'Only a "Staff" user can edit here.', ATTMGR::TEXTDOMAIN ).'<br>';
				printf( '<div class="alert alert-error">%s</div>', $error_msg );
			}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 *	Scheduler for admin
	 */
	public static function admin_scheduler( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'name_key' => 'display_name',
				),
				$atts
			)
		);
		ob_start();
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'There are no staff.', ATTMGR::TEXTDOMAIN ) );
		} else {
			if ( $attmgr->user['operator']->can_edit_admin_scheduler() ) {
				$startdate = $attmgr->page['startdate'];
				list( $y, $m, $d ) = explode( '-', $startdate );
				$m = intval( $m );
				$d = intval( $d );
				$starttime = mktime( 0, 0, 0, $m, $d, $y );

				$term = 7;
				$endtime = mktime( 0, 0, 0, $m, $d + $term, $y );
				$enddate = date( 'Y-m-d', $endtime );

				// Head
				$head = '';
				for ( $i = 0; $i < $term; $i++ ) {
					$t = $starttime + 60*60*24*$i;
					$w = date( 'w', $t );
					$date = '';
					$date = sprintf( '<span class="date">%s</span><span class="dow">(%s)</span>', 
						apply_filters( 'attmgr_date_format', $date, $t ),
						ATTMGR_Calendar::dow( $w )
					);
					$head .= sprintf( '<th class="%s">%s</th>'."\n", ATTMGR_Calendar::dow_lower( $w ), $date );
				}
				$head = sprintf( '<tr><th>&nbsp;</th>'."\n".'%s</tr>', $head );

				// body
				$table = '';
				$table = apply_filters( 'attmgr_schedule_table_name', $table );
				$query = "SELECT * FROM $table "
						."WHERE staff_id = %d "
						."AND ( date>=%s AND date<= %s ) ";

				$body = '';
				$staff = ATTMGR_User::get_all_staff();
				foreach ( $staff as $s ) {
					$staff_id = $s->data['ID'];
					$records = $wpdb->get_results( $wpdb->prepare( $query, array( $staff_id, $startdate, $enddate ) ), ARRAY_A );
					$schedule = array();
					if ( !empty( $records ) ) {
						foreach ( $records as $r ) {
							$schedule[ $r['date'] ] = $r;
							$schedule[ $r['date'] ]['starttime'] = substr( $schedule[ $r['date'] ]['starttime'], 0, 5 );
							$schedule[ $r['date'] ]['endtime'] = substr( $schedule[ $r['date'] ]['endtime'], 0, 5 );
						}
					}
					$param = array(
						'start'    => $attmgr->option['general']['starttime'],
						'end'      => $attmgr->option['general']['endtime'],
						'interval' => $attmgr->option['general']['interval'],
						'class'    => array(),
					);

					$line = '';
					for ( $i = 0; $i < 7; $i++ ) {
						$d = date( 'Y-m-d', $starttime + 60*60*24*$i );
						$w = date( 'w', $starttime + 60*60*24*$i );
						$dow = ATTMGR_Calendar::dow( $w );

						$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['starttime'] : '';
						$param['name'] = sprintf( '%s_post[%d][%s][starttime]', ATTMGR::PLUGIN_ID, $staff_id, $d );
						$st = ATTMGR_Form::select_time( $param );

						$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['endtime'] : '';
						$param['name'] = sprintf( '%s_post[%d][%s][endtime]', ATTMGR::PLUGIN_ID, $staff_id, $d );
						$et = ATTMGR_Form::select_time( $param );

						$param['current'] = ( isset( $schedule[ $d ] ) ) ? $schedule[ $d ]['workplace'] : '';
						$param['name'] = sprintf( '%s_post[%d][%s][workplace]', ATTMGR::PLUGIN_ID, $staff_id, $d );
						$wp = ATTMGR_EXT_Workplace::select_workplace( $param );

						$off = sprintf( '<label><input type="checkbox" name="%s_off[%d][%s]" value="1" />%s</label>', ATTMGR::PLUGIN_ID, $staff_id, $d, __( 'DEL', ATTMGR::TEXTDOMAIN ) );
						$line .= sprintf( '<td>%s<br>%s<br>%s<br>%s</td>'."\n", $st, $et, $wp, $off );
					}
					$portrait = null;
					$portrait = ATTMGR_Function::get_portrait( $portrait, $s );
					$name = $s->data[ $name_key ];
					if ( ! empty( $s->data['user_url'] ) ) {
						$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
					}
					$body .= sprintf( '<tr><td class="portrait">%s<br>%s</td>%s</tr>'."\n", $portrait, $name, $line );
				}

				// Return url
				$url = get_permalink( get_page_by_path( $attmgr->option['specialpages']['admin_scheduler'] )->ID );
				$query_string = ( strstr( $url, '?' ) ) ? '&' : '?';
				$url .= ( empty( $attmgr->page['qs']['week'] ) ) ? '' : $query_string.'week='.$startdate;

				$format = <<<EOD
%NAVI%
<form id="%FORM_ID%" method="post">
<table class="%CLASS%">
%HEAD%
%BODY%
</table>
%NONCE%
<input type="hidden" name="returnurl" value="%RETURN_URL%" />
<input type="hidden" name="action" value="%ACTION%" />
<input type="submit" name="submit" value="%SUBMIT%" />
</form>
%MESSAGE%
EOD;
				$search = array(
					'%NAVI%',
					'%FORM_ID%',
					'%CLASS%',
					'%HEAD%',
					'%BODY%',
					'%NONCE%',
					'%RETURN_URL%',
					'%ACTION%',
					'%SUBMIT%',
					'%MESSAGE%',
				);
				$replace = array(
					ATTMGR_Calendar::show_navi_weekly( $startdate ),
					ATTMGR::PLUGIN_ID.'_admin_scheduler',
					ATTMGR::PLUGIN_ID.'_admin_scheduler',
					$head,
					$body,
					wp_nonce_field( ATTMGR::PLUGIN_ID, 'onetimetoken', true, false ),
					$url,
					ATTMGR::PLUGIN_ID.'_update_by_admin',
					__( 'Update', ATTMGR::TEXTDOMAIN ),
					'',
				);
				$subject = str_replace( $search, $replace, $format );
				echo $subject;
			} else {
				printf( '<div class="alert alert-error">%s</div>', __( 'Permission denied.', ATTMGR::TEXTDOMAIN ) );
			}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 *	'attmgr_shortcode_weekly_attendance'
	 */
	public static function weekly_attendance( $attendance, $args ) {
		extract( $args );	// $date, $schedule, $current_staff
		if ( ! empty( $schedule['workplace'] ) ) {
			$attendance = $attendance.'<br>'.$schedule['workplace'];
		}
		return $attendance;
	}

	/**
	 *	'attmgr_shortcode_weekly_all_attendance'
	 */
	public static function weekly_all_attendance( $attendance, $args ) {
		extract( $args );	// $date, $schedule, $current_staff
		if ( ! empty( $schedule['workplace'] ) ) {
			$attendance = $attendance.'<br>'.$schedule['workplace'];
		}
		return $attendance;
	}

	/**
	 *	'attmgr_shortcode_daily_values'
	 */
	public static function daily_values( $array, $args ) {
		list( $search, $replace ) = $array;
		/*
	    $search = Array(
	            [0] => %PORTRAIT%
	            [1] => %NAME%
	            [2] => %ATTENDANCE%
        )

	    $replace = Array(
	            [0] => tm01
	            [1] => John Doe
	            [2] => 13:00 ~ 22:00
        )

		$args = Array(
	    	[result] => Array(
	            [staff] => Array(
                    [0] => ATTMGR_User Object(
                        [data] => Array(
                            [ID] => 2
                            [user_login] => staff1
                            [user_nicename] => staff1
                            [user_email] => staff1@localhost.localdomain
                            [user_url] => http://example.com/john-doe/
                            [user_registered] => 2016-08-01 09:45:00
                            [user_activation_key] => 
                            [user_status] => 0
                            [display_name] => John Doe
                            [caps] => Array(
                                [subscriber] => 1
                            )
                            [cap_key] => wp_capabilities
                            [roles] => Array(
                                [0] => subscriber
                            )
                            [allcaps] => Array(
                                [read] => 1
                                [level_0] => 1
                                [subscriber] => 1
                            )
                            [filter] => 
                            [first_name] => John
                            [last_name] => Doe
                            [nickname] => staff1
                            [attmgr_ex_attr_staff] => 1
                            [attmgr_mypage_id] => 9
                        )
                        [loggedin] => 
                    )
                )
	            [attendance] => Array(
	                [2] => Array(
	                    [date] => 2016-08-19
	                    [starttime] => 13:00:00
	                    [endtime] => 22:00:00
	                    [staff_id] => 2
	                    [absence] => 0
	                    [lateness] => 
	                    [workplace] => Shop A
	                )
    		    )
    		)
	    	[current_staff] => ATTMGR_User Object(
	            [data] => Array(
	            	...
                )
	            [loggedin] => 
	        )
		)
		*/
		$i = array_search('%ATTENDANCE%', $search );
		if ( $i === false ) {
			array_push( $search, '%ATTENDANCE%');
			array_push( $replace, '%ATTENDANCE%');
		} else {
			$current_staff_id = $args['current_staff']->data['ID'];
			$replace[ $i ] = $replace[ $i ].'<span>'.$args['result']['attendance'][ $current_staff_id ]['workplace'].'</span>';
		}
		return array( $search, $replace );
	}

	/** 
	 *	Plugin menu
	 */
	public static function add_menu() {
		add_submenu_page(
			ATTMGR::PLUGIN_ID.'-general',
			__( 'Attendance Manager', ATTMGR::TEXTDOMAIN ).' '.__( 'Workplace Setting', ATTMGR_EXT_Workplace::TEXTDOMAIN ),
			__( 'Workplace Setting', ATTMGR_EXT_Workplace::TEXTDOMAIN ),
			'administrator',
			ATTMGR::PLUGIN_ID.'-workplace',
			array( 'ATTMGR_EXT_Workplace', 'setting_page' )
		);
	}

	/**
	 *	Plugin setting page
	 */
	public static function setting_page( $args = null ) {
		global $wpdb;
		extract(
			wp_parse_args(
				$args,
				array(
					'title' => __( 'Attendance Manager settings', ATTMGR::TEXTDOMAIN ),
					'options_key' => ATTMGR_EXT_Workplace::PLUGIN_ID,
				)
			)
		);

		$options_group = '';
		if ( isset( $_SERVER['QUERY_STRING']) ) {
			parse_str( $_SERVER['QUERY_STRING'], $qs );
			if ( isset($qs['page'] ) ) {
				$options_group = substr( $qs['page'], strlen( ATTMGR::PLUGIN_ID.'-' ) );
			}
		}
		$message = '';
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			// Update
			if ( isset( $_POST[ATTMGR::PLUGIN_ID.'_options'] ) ) {
				$options = get_option( $options_key );
				$options[ $options_group ] = $_POST[ATTMGR::PLUGIN_ID.'_options' ][ $options_group ];
				update_option( $options_key, $options );
				$message = __( 'Settings are updated', ATTMGR::TEXTDOMAIN );
			}
			if ( $message ) {
				echo '<div id="message" class="updated fade"><p>'.$message.'</p></div>';
			}
		}
		$options = get_option( ATTMGR_EXT_Workplace::PLUGIN_ID );
?>
<div id="<?php echo $options_key; ?>" class="wrap">
<?php screen_icon( 'options-general' ); ?>
<h2><?php echo esc_html( $title ); ?></h2>
<?php
		switch ( $options_group ) {
			case 'workplace':
?>
<div class="metabox-holder has-right-sidebar">
<div id="post-body">
<div id="post-body-content">
<div class="postbox">

<h3><span><?php _e( 'Workplace Setting', ATTMGR_EXT_Workplace::TEXTDOMAIN ); ?></span></h3>
<div class="inside">
<form method="post" action="<?php echo get_admin_url().basename( $_SERVER['SCRIPT_NAME'] ).'?page='.ATTMGR::PLUGIN_ID.'-workplace'; ?>"> 

<table class="form-table">
<tr valign="top"><td colspan="2"><strong><?php _e( 'Workplace Setting', self::TEXTDOMAIN ); ?></strong></td></tr>

<tr valign="top">
<th scope="row"><?php _e( 'Workplace List', self::TEXTDOMAIN ); ?>:</th>
<td>
<textarea name="attmgr_options[workplace]" id="" rows="5" style="width:100%"><?php echo $options['workplace']; ?></textarea> 
<p class="description"></p>
</td>
</tr>

<tr><td colspan="2"><hr /></td></tr>
<tr valign="top">
<th scope="row">&nbsp;</th>
<td>
<input type="hidden" name="action" value="workplace" />
<input type="submit" name="save" class="button-primary" value="<?php _e( 'Update', ATTMGR_EXT_Workplace::TEXTDOMAIN);?>" class="large-text code" />
</td>
</tr>
</table>

</form>
</div>
</div>
</div>
</div>
<div class="inner-sidebar">
<?php do_action( ATTMGR_EXT_Workplace::PLUGIN_ID.'_update_info' ); ?>
<?php do_action( ATTMGR::PLUGIN_ID.'_extensions_info' ); ?>
</div>
</div>
 <?php
				break;
			default:
		}
?>
</div>
<?php
	}

	/** 
	 *	UPDATE INFO (in PLUGIN OPTION PAGE)
	 */
	public static function update_info() {
		require_once( ABSPATH.'wp-admin/includes/plugin.php' );
		$mypluginfile = dirname( plugin_dir_path( __FILE__ ) ).'/'.self::PLUGIN_FILE;
		$pinfo = get_plugin_data( $mypluginfile );
		/*
		Array(
		    [Name] => ATTMGR Extension: Workplace
		    [PluginURI] => http://attmgr.com/extensions/codes/workplace/
		    [Version] => 1.0.0
		    [Description] => Add workplace to schedule.スケジュールに日ごとの勤務先を追加。
		    [Author] => tnomi
		    [AuthorName] => SUKIMALAB
		    [AuthorURI] => http://sukimalab.com
		    [TextDomain] => attmgr-ext-workplace
		    [DomainPath] => /languages/ 
		    [Network] => 
		    [Title] => ATTMGR Extension: Workplace
		)*/
		$url = self::FEED_URL;
		$feed = fetch_feed( $url );
	?>
	<div class="postbox">
		<h3><span><?php _e( 'Updates', self::TEXTDOMAIN ); ?></span></h3>
		<div class="inside">
			<p><a href="<?php echo $pinfo['PluginURI']; ?>" target="_blank"><?php echo $pinfo['Name']; ?></a><br>
				<?php printf( 'Installed Version: %s', self::PLUGIN_VERSION ); ?></p>
			<p><?php _e( 'Refer to the newest version below.', self::TEXTDOMAIN ); ?></p>
			<hr>
		<?php
		if ( !empty( $feed->data ) ) {
			$feed->set_cache_duration( 60*60 );
			$feed->init();
			$param = sprintf( 'title=%s&items=3&show_summary=0&show_author=0&show_date=0', __( 'Updates', self::TEXTDOMAIN ) );
			@wp_widget_rss_output( $feed, $param );
		}
		else {
			printf( '(%s)', __( 'Feed not found', self::TEXTDOMAIN ) );
		}
		?>
		</div>
	</div>
	<?php
	}

	/**
	 *	Show today's workplace shortcode [attmgr_today_place id="xx"]
	 */
	public static function today_place( $atts, $content = null ) {
		global $attmgr;
		extract(
			shortcode_atts(
				array(
					'id'   => null,
					'none' => '',
				),
				$atts
			)
		);
		$user = new ATTMGR_User( $id );
		$r = $user->is_work( $attmgr->page['startdate'] );
		if ( ! empty( $r ) ) {
			return $r['workplace'];
		} else {
			return $none;
		}
	}

	/**
	 *	Daily schedule
	 */
	public static function daily_schedule( $html, $atts, $content = null ) {
		global $attmgr, $wpdb;
		extract(
			shortcode_atts(
				array(
					'guide' => '',
					'past'  => true,
					'name_key' => 'display_name',
					'shop'  => null,
				),
				$atts
			)
		);
		$staff = ATTMGR_User::get_all_staff();
		if ( empty( $staff ) ) {
			printf( '<div class="alert alert-caution">%s</div>', __( 'No staff are registered yet.', ATTMGR::TEXTDOMAIN ) );
		} else {
			if ( $shop ) {
				foreach ( $staff as $s ) {

				}
			}
			ob_start();
			$date = $attmgr->page['startdate'];
			$starttime = $attmgr->option['general']['starttime'];
			$endtime = ATTMGR_Form::time_calc( $attmgr->option['general']['endtime'], 0, false );

			if ( ! empty( $guide ) ) {
				$args = array(
					'date'  => $date,
					'guide' => $guide,
					'past'  => $past,
					'html'  => '',
					'begin' => $attmgr->page['begin_date'],
				);
				$args = apply_filters( 'attmgr_shortcode_daily_guide', $args );
				echo $args['html'];
			}

			$now = current_time('timestamp');
			$now_time = date( 'H:i', $now );

			// e.g. 19:00 ~ 04:00
			$result = ATTMGR_User::get_working_staff( $date );
			if ( $attmgr->page['midnight'] ) {
				printf( '<div class="alert alert-normal">%s</div>', sprintf( __( '[Open %s~%s] Now %s ', ATTMGR::TEXTDOMAIN ), $starttime, $endtime, $now_time ) );
			}

			extract( $result );		// $staff, $attendance
			if ( $shop ) {
				$org = $staff;
				$staff = array();
				foreach ( $org as $i => $s ) {
					if ( $shop == $attendance[$s->data['ID']]['workplace'] ) {
						$staff[$i] = $s;
					}
				}
			}
			if ( empty( $staff ) ) {
				printf( '<div class="alert">%s</div>', __( 'There are no staff today.', ATTMGR::TEXTDOMAIN ) );
			} else {
				echo '<ul class="staff_block">'."\n";
				foreach ( $staff as $s ) {
					// Format
					$format = <<<EOD
<li>
	<div class="thumb">
		%PORTRAIT%
	</div>
	<div class="post-info">
		<div class="name">%NAME%</div>
		<div class="attendance">%ATTENDANCE%</div>
	</div>
</li>
EOD;
					$format = apply_filters( 'attmgr_shortcode_daily_format', $format );

					// Search: Key
					$search = array(
						'%PORTRAIT%',
						'%NAME%',
						'%ATTENDANCE%',
					);

					// Repelace: Value
					$portrait = null;
					$portrait = ATTMGR_Function::get_portrait( $portrait, $s );
					$name = $s->data[ $name_key ];
					if ( ! empty( $s->data['user_url'] ) ) {
						$name = sprintf( '<a href="%s">%s</a>', $s->data['user_url'], $name );
					}
					$starttime = apply_filters( 'attmgr_time_format', $attendance[$s->data['ID']]['starttime'] );
					$endtime   = apply_filters( 'attmgr_time_format', $attendance[$s->data['ID']]['endtime'] );

					$replace = array(
						$portrait,
						$name,
						sprintf( '%s ~ %s', $starttime, $endtime ),
					);
					$args = array(
						'result' => $result,
						'current_staff' => $s
					);

					list( $search, $replace ) = apply_filters( 'attmgr_shortcode_daily_values', array( $search, $replace ), $args );
					$line = str_replace( $search, $replace, $format );
					echo $line;
				}
				echo "</ul>\n";
			}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

}
?>
