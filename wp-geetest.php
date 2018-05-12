<?php
/*
Plugin Name: 极验验证V3.0
Plugin URI: http://www.geetest.com/
Description: 极验验证是全球首家新一代行为式验证™技术服务提供商，为给互联网世界的人们带去“更安全、更便捷、更具智慧的验证”为目标而不断前行。极验验证首度提出“行为式验证”的全新验证理论，并构建出“行为沙盒”的验证框架，利用自主研发的“多重行为判别智能算法”，达到了令传统验证技术难以到达的高度，至此开创验证安全2.0新时代。
Version:  5.3.3
Author: 极验验证, Jaggle
Email: tanxu@geetest.com
Author URI: http://www.geetest.com/
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('GEE_NAME', plugin_basename(__FILE__));
define('GEE_DIR', plugin_dir_path(__FILE__));
define('GEE_URL', plugin_dir_url(__FILE__));

require_once('geetest.class.php');
//ob_start — 打开输出控制缓冲
ob_start();

if (class_exists("Geetest")) {
    $geetest_plugin = new Geetest();
    $geetest_plugin->start_plugin();
}
?>