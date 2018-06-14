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
define('DB_NAME', 'peachtree_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'D2hHDQq_L_7yDP|ZbA|_+(S;8UgRWRcHl=1#z>GZg@HapPE}AJ22PTfe^[/`T8Wm');
define('SECURE_AUTH_KEY',  ')?#BQz]:WK+CndvZ&dK~ QB.c@2&mYpu<?7]Om[&$!jxr5o(4<@@@=D}j}-yC1Dx');
define('LOGGED_IN_KEY',    'ut^]N]9o?w>6Tw=iO.kaVBiPLMBfF,Xi$Z_;D/@}U^S&>SY!ej|[8+9:(i[orAs/');
define('NONCE_KEY',        '97jO[xn_-jI<,`WDxLYA%@ `}`qV+ras=T)K<(y)XJ!1*V!SQeMi0P?W]]VCPjqI');
define('AUTH_SALT',        'Lp.L1RKb<UQ`IRmj7Xr^1-L>b&a?X43MqV- XMeL3tsZVJa&^I4UyG|pP+.7KP=<');
define('SECURE_AUTH_SALT', 'NQG;n=5jZx@%<t}%DneqF|`n 9q@-y<*%jlO*8Vd{b,?1LnQ[-j6MG?WY,MXw*yL');
define('LOGGED_IN_SALT',   ']Kct=YvDKchln|R:&.n([`1-=.#}RJLu$ify T{Sd*sOYWG9yoBr^h$;(7I]E;,S');
define('NONCE_SALT',       'x@Hau!S?%>wJky*1~~A/3Fa}sn]cndDZ=i<dpok0]_@@W9I5.]]Y*^%<F)->7fXx');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
