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
define('DB_NAME', 'intimation');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'qBwKYy,VEO/BKpwHyl;}ua_w]:GbD,)(]][.~X{<d[16tY.=a]ptm0DB9,t,X?+3');
define('SECURE_AUTH_KEY',  '*5?&2S8Gk(9dv02l0H%UbGJh6Hl`5e@ jD?l(xiR6P{o9SY<|who,CH!Zm1dzOqm');
define('LOGGED_IN_KEY',    '5?C$^?|~Ug#@!)wt<bB-e=%Uak[a-x;tqVp[ ~wr`bbY49t)QBOi<$O=o.T15c 0');
define('NONCE_KEY',        ',8$V,$8/JIJR,XX(u6mnJ:V)oit(yGX=p1~L KReTOL(WCfJ{Q3x`(:c{;9k3!Br');
define('AUTH_SALT',        'W&4}pB)}s34_XOZ&oYFD|3R9]-K+*Pv{XX3K6QJ;/F-:4oJq5QFx3bpUD=;Z:-`2');
define('SECURE_AUTH_SALT', 'UV@tW7dcdY7n`H8-&1AzO$s6ELE$56yF`[IP$%cU8vYB:B)9RwYt^Ie20E#znOGt');
define('LOGGED_IN_SALT',   's`m)>8LfG_8-]#&]}A7uj!qgGhsfSrQBvB(HnY4ebo>y]z.8lV:70Mp ~4&iRB?{');
define('NONCE_SALT',       'AF)h*k1@+i-ugh|,pn=Kx(hY)O-1=0gIHn3:Ac(3Q7p{wZ@e8vygL$YD2r3|Cebj');

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
