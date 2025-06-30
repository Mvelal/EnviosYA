<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'NRfkl:C~JHXy[6:{WS^0dXnO,cbVDI9+-~$l,(cWHUl}WiZ623V5XaX,lY*<- eS' );
define( 'SECURE_AUTH_KEY',   'rhC}P8~1Z:`J$Guu&!U+;f^G%(8u(Qih|Y7zTUndGi?nkiG@tiZM*u9}fwV,g0_2' );
define( 'LOGGED_IN_KEY',     'DdCebp:zCD icMsX8X=]z[.<hKWih}93[%.@;1.N`xO0}dU]yQLbKnajV@Rv1A-$' );
define( 'NONCE_KEY',         'MM,65(y99[*(6;$C_7.a<uf0SMcj(J=sWqML0Z;4~Ci_9p9-C6:uO@DD6-&.`4i~' );
define( 'AUTH_SALT',         'cGeC2?(Iz)Am0XQdNJNv?NI^K)8260A-Z-*aJJ1uDO{Q~@_:?r*MD64G:d9!~&k3' );
define( 'SECURE_AUTH_SALT',  'ats d`],o)y[;c)mgiEk{eRZjP4cb1AvdIxqoa}==GmWfMU7Q(gr0K<YPYRz=0C7' );
define( 'LOGGED_IN_SALT',    'yQA$2ksKPP,V1DyaD>sIL6<AxLGl1iJ`m.mq%iL1tY57v)yeU(/%NXY[q?CZDWfG' );
define( 'NONCE_SALT',        '8=(#f]:jFVT1A7[B1]Jbb`=vm{s5E[h-ON!7gd{0LFg8?hp2vkH&27IR.ufs9Z?A' );
define( 'WP_CACHE_KEY_SALT', 'v{iN!GdfI)R}E9s:<58- -4({qC*S2QWaXGzh-u-mPNy.}38tXW>24!]TC*60<N[' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
