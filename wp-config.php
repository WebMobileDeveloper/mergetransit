<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '1iovk97@6r23w8NBZ%9DdgO&GSn2} qy`h(g<_$CR8-t37v812B%oIBy!b2g&|J*' );
define( 'SECURE_AUTH_KEY',  'Fh^SKbD/B8@[zIXN+}k/@h{#*6vs?a/.%!YjupXs-oTky%Q208l4_|6pB(k~E:E%' );
define( 'LOGGED_IN_KEY',    '@BBQZY$zD3o>eVzgx-zDYxeNRqqfzu&7Q p_Si~dx8N|DV{7AaU!LpcVI1e7h_H*' );
define( 'NONCE_KEY',        'KDpC><I8rNqlq+JH TSDs2}1_V~ypgs}2elHm1TquU^.SZAig_IJ jVb<YSo2+#I' );
define( 'AUTH_SALT',        'l5R8|#@0VY_NQ5^_,pFgt*Un7H@2JR %JY,t.n69#(uC$w1O `PYhB:>CVJyK6^ ' );
define( 'SECURE_AUTH_SALT', '6LzbFFjmcuwSSZl1f0b?UKYMKNK4K(~7?Z`8QgZ8`Ut4c{Xz|)bw.>%zGpB9FNQ1' );
define( 'LOGGED_IN_SALT',   'gptN_haWyZ+yKwp<P_W|@G9s|bRo*pndkH9b|w &FoBv: vd[{.!:{V^X=%Pd 7j' );
define( 'NONCE_SALT',       '[9fPs9B4}y#sB+;o;?uC=DtQG_XDJ([?^u^K]ezhxtoY]!jTpF#WtRChgCS(E{|J' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );


define( 'ALLOW_UNFILTERED_UPLOADS', true );


/** Disable the automatic WordPress update. */
define( 'AUTOMATIC_UPDATER_DISABLED', true );


/** Disable the file editor. */
define( 'DISALLOW_FILE_EDIT', true );


/** Set up the memory limit. */
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '256M');


/** Set up the execution time limit. */
set_time_limit( 300 );


/** Set up the autosave interval. */
define( 'AUTOSAVE_INTERVAL', 60 );


/** Set up the post revisions . */
define( 'WP_POST_REVISIONS', 300 );


/** Set up the empty trash interval. */
define( 'EMPTY_TRASH_DAYS', 7 );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
