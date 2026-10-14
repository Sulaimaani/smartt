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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',          '5Z;VB{Yo;Yl;VM1W 4[hgmjm$ &U.h%Ip:Ycr*FT?!O)JbN;QUT 7C0-o]2w.z}D' );
define( 'SECURE_AUTH_KEY',   ']Ev_0< td$S%HauV1th|j`qgzwR;e7.z0v^FlV8|q*NK%HA?%8rP;g:]3.b!3Hdv' );
define( 'LOGGED_IN_KEY',     '.4Pxb&xFGk<PINw#cNr#.AjQBZI08Js:UR?u><qqo)I7:Q2PKY@6Saj:7wRQ8wg2' );
define( 'NONCE_KEY',         '39w=99.WaYt@`pg_Zkt&fQ;8*]!Kp=}H,_[|7GrHPsT*6V+,Vilu#bW_6rrwbbAy' );
define( 'AUTH_SALT',         'V%fKDOdd}[4Fdk4i1H+Y[EgVtl#2gL!8~|];,rw_pgO?mP%!]xQR:cp-XU?ef-IR' );
define( 'SECURE_AUTH_SALT',  ')WxpJ)X{,nNVL<SN[9c#:jlhT,I7Y$J:pud8>O$KuJ@^t86j@!Iw]UYmhe+`@p6j' );
define( 'LOGGED_IN_SALT',    'o09.}#- rlbBLX;tpm1Oy8e(!PKnI.X)=Fr4wGp8rv*jLQWCOXr3jk5{a>.zvc]]' );
define( 'NONCE_SALT',        '>RJ-_<H<|4Gp9q5j0hQ1bJ73d=(=<h7{)2O2dH2)U:}=eETiv.gq%D.EMAiGJ)d5' );
define( 'WP_CACHE_KEY_SALT', '}TmNb3Hy=+`R?v@ [:Xk45RHhu%GDq@wf6_^fuCDeZ5i7+#CZq]`gRb(xTYN=C&?' );


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
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true );
	define( 'WP_DEBUG_DISPLAY', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
