<?php
/**
 *
 *
 * @category NY Times API Project
 * @author: Pascal Brammeier
 * Date: 3/14/2016
 *
 */
global $config; // ensure this variable is available to entire application

//TODO: Someday we should account for people living someplace besides Denver
date_default_timezone_set('America/Denver');

$config = parse_ini_file('../config.pwd.ini'); // load protected settings

