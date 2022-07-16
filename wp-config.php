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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('REVISR_GIT_PATH', ''); // Added by Revisr
define('REVISR_WORK_TREE', '/var/www/vhosts/mrtrilb.com/httpdocs/'); // Added by Revisr
define( 'DB_NAME', 'wp_wneod' );

/** MySQL database username */
define( 'DB_USER', 'wp_i0ygz' );

/** MySQL database password */
define( 'DB_PASSWORD', 'L*ka^%C4&1N1yt3j' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'H%Q1!_9cZxY~58B-|S42u)5NaZ*8Bnbx!YFl5/Hvdl9qw4kv%uY7Pf_g%JU*~@xN');
define('SECURE_AUTH_KEY', 'wqPsF/Yh#vy4ka8T++x#%2h1Dq%_r64+614QE:_*9@n9u-Cxxirh!O+V3P)M99~e');
define('LOGGED_IN_KEY', 'G3OW7DfC7l/H&W2nsd7is0;@-6@&A6K~K[&QW+S#Y!fQ7t[!Y7#M+TH4&]N7[7z@');
define('NONCE_KEY', 'e7XeR1/%eW05G1(2tX(M0I4evQ*hZcH+@8B29G:+Cxn00%BAXF2d&0GEC3-@AJ_0');
define('AUTH_SALT', 'wDhu27(6O!No!h9%y(eY5i@@|F#1T-O@@Kk%]6)d&9z#7I+#4i3;Xu1V6u6r4_w6');
define('SECURE_AUTH_SALT', '[YQ@2C+/tj2ix155k7_27F/;3(9t%a1ex)gr+;68Y_v~n99JndFp*Jm|&)T2+X1K');
define('LOGGED_IN_SALT', '7xWaID4U8x&2#1+704U7b&1Qk&wGdN6Z7sl|)Kik5r~SDy3_i~]i)1~7wcRM10vs');
define('NONCE_SALT', 'd((u!(99(|k+1j|aA0B;Apg_ALi9h/sT[~s3;dp1()zm[7e7-1!JFJ~Fc-Ky#|6%');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '58DQgTJc_';


define('WP_ALLOW_MULTISITE', true);
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
