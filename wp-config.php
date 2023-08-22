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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vdt1410' );

/** Database username */
define( 'DB_USER', 'vdt1410' );

/** Database password */
define( 'DB_PASSWORD', '12345678' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'UN3>Jcs]?-.lA<yq>sELVOQT$<l#N?%Fk|vH#oJ`io0])j]d:6Y%m{!W>f^N-C]g' );
define( 'SECURE_AUTH_KEY',  'CKHASE&QPj:+60LQ>Ce{L[YwCxuDgpm)5-3J%WW[mH 2pf(Q1b8mz,7+*8jk4bqP' );
define( 'LOGGED_IN_KEY',    '2 q{w$itQIS>=YMkV]wexXo{g2g}[2u]M.Mi&0FViP58}V.%u5FR7P4Ie Q7Nz`0' );
define( 'NONCE_KEY',        '8!N?u~,2/Ti>`!fLE%loQEIVhKtlPFxP6@I}Q1e  _|g|/h*&_wQ5@pb1T[maq,k' );
define( 'AUTH_SALT',        'a__8S4]+D:]=ne+}T{,2}>{{$1aUDJGO^~MD)+|ii(;+n1h -Ob;x9ph]JyvF#`0' );
define( 'SECURE_AUTH_SALT', 'dlHv=xYgzvwl.5%K|bZh/f1}d@7haOw!%3Zmz@l2&^8)$nE}S&CVj%-UBp*O%stR' );
define( 'LOGGED_IN_SALT',   '`bKoFI_sx[+JNSrcU %7y7r)<20hs3TDA{;fM?jp|hRMhhbdJbr_#Bl0,(EVuB8_' );
define( 'NONCE_SALT',       '[AX4m(?jpuMZ}rC^R@0$^ugYc#|8k?=XP!s,M9!<N,ri)/W?+ieOm3!8C[$fxFQr' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
