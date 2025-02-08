<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'u206029413_obzCK' );

/** Database username */
define( 'DB_USER', 'u206029413_Ri0Xk' );

/** Database password */
define( 'DB_PASSWORD', 'Ez5ICIqueN' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          'R2p_v*r.v_M4qGXU/xp6u=>.<O*etf~PLvNH$c& (TITGlGy$w`zI-fgVed*dul-' );
define( 'SECURE_AUTH_KEY',   'noUCHKF@Wt6=<-gnOG*h0iZ4ts0,N&gK<9vT,^5ctX6~!x906ML!-po+^xiAn66`' );
define( 'LOGGED_IN_KEY',     ' gIsX<5u#WgxI5mv)^89p,QgQ-cxS[t$|&8BZ5,@t8-$S^ioNIC(S%g*%E4|syWQ' );
define( 'NONCE_KEY',         '<?;:n}|E [b.hPncXsEVLd7SOhP?jvJpe)`AI2_l)xg7zDdF+0La&/oF*]O$VZu&' );
define( 'AUTH_SALT',         'RqAh*2/.`))4=MNz}J~qM3L)amfibP:92DP -a%EzH*4SP={U^k<ns?0fQurDXmt' );
define( 'SECURE_AUTH_SALT',  'S,8m,mi>]]&7w%F_f9.lZiHa4+=3Z|+FDw-S1v%dj:M4V3/Or][@!?L+z%/65l!3' );
define( 'LOGGED_IN_SALT',    'z?Yk$e?w$Ie} /7M!D5h EbN:vmWyjk|xW#4e!f~P%$>j(F~Yb]/W-f-V!p>,G3r' );
define( 'NONCE_SALT',        '%iA}n+D3k*lPV38LE4{@lC>8hP[QnUTB3wf(>xZ++e/vpXQ=|s[^6,fXO}b5Qhri' );
define( 'WP_CACHE_KEY_SALT', 'O3-XDx?7_E#Bu*A~=-nR,f8ZLDZrTGyZdi]yJp&?&I+p`jUy#k(4tI-X[1:V #?L' );


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

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'fec507d6cc2f2c26a69a023e9995287b' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
