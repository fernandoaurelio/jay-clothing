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
define('DB_NAME', 'jclothing');

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
define('AUTH_KEY',         '^@:MCobynSk*2ESu(r9$?z_eO#;i@p^ET.s;eMb7l?3~$0/a:Dn>1HL>&M;!T3qm');
define('SECURE_AUTH_KEY',  'PauqL4]y}j6cqUfR!-~8q0?CiT^6n{_QxkyHz}IyzGyJ<h>sy@N~8C2SpZ*Y3&Ch');
define('LOGGED_IN_KEY',    ',qy(5fG1qiPMA/~.q;VXtYbm.GC*WXQ5F&AQDv@2yVAe ZA~Tgm)[Zdy.}Y0)$X!');
define('NONCE_KEY',        '%~t+d5-}52;JW(Kn0tZ[?Z?sDo8f[)nBi)E)hmgMgdx(A2<v4?U#S+AfmBp%4`}t');
define('AUTH_SALT',        'l$1B_AU<%J#)G)y8g+N@:b-iI?J;qTfGk^!#,4C{e e2PP|MI1gy]FeayZM&1X>T');
define('SECURE_AUTH_SALT', 'S7bnM4FgJna4 b-xT6A4@gy=b6(*b#j+#k/+`1FEjXM0=}!I@ZIXr2 {v[]xd)w!');
define('LOGGED_IN_SALT',   '1vgSO:#RQ!1,pX;X5Dg{YhHKbmVEdep87Oz5FW#~<0poj37?|TOxu1R zdl%Xapr');
define('NONCE_SALT',       'xW,RLnw|ALap?^(i!-c(C},yY-Ypjw+oA{$aYu;;6^7`Z=m5H%HUC)iWwV&pH1vV');

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
