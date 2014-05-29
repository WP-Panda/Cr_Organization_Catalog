<?php
	/**
		* @package Cr_Organization_Catalog
		* @version 1.0.4
	*/
	/**
		*Plugin Name: Cr_Organization_Catalog
		*Plugin URI: http://mywordpress.ru/support/viewtopic.php?pid=129090#p129090
		*Description:  Плагин предназначен для вывода индивидуальной информации для разных регионов.
		*Version: The Plugin's Version Number, e.g.: 1.00
		*Author: Maksim (WP_Panda) Popov
		*Author URI: http://mywordpress.ru/support/profile.php?id=36230
		*License: A "Slug" license name e.g. GPL2
	**/
	
	/*  Copyright 2014  Wp_Panda  (email : yoowordpress@yandex.ru)
		
		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.
		
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
		
		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	/*----------------------------------------------------------------------------*/
	/* setting constants
	/*----------------------------------------------------------------------------*/
	define('CR_ORGANIZATION_CATALOG_DIR', plugin_dir_path(__FILE__));
	define('CR_ORGANIZATION_CATALOG_URL', plugin_dir_url(__FILE__));
	
	/*----------------------------------------------------------------------------*/
	/* includes components
	/*----------------------------------------------------------------------------*/
	
	require_once('register-tax.php');
	require_once('functions.php');
	require_once('templater.php');
	
	/*----------------------------------------------------------------------------*/
	 /* includes backend scripts & styles
	 /*----------------------------------------------------------------------------*/
	
		function cr_csv_importer_backend() {
			wp_register_script( 'cr-organization-catalog-backend-script', CR_ORGANIZATION_CATALOG_URL . 'assets/js/cr-organization-catalog-backend-script.js', '', '1.00');
			wp_enqueue_script('cr-organization-catalog-backend-script');
		}	
		add_action( 'admin_enqueue_scripts', 'cr_csv_importer_backend' );
	
	/*----------------------------------------------------------------------------*/
	 /* includes frontend scripts & styles
	 /*----------------------------------------------------------------------------*/
	

		function cr_organization_catalog_frontend() {
			wp_register_style( 'cr-organization-catalog-frontend-style', CR_ORGANIZATION_CATALOG_URL  . 'assets/css/cr-organization-catalog-frontend-style.css', '', '1.0.0');
			wp_enqueue_style('cr-organization-catalog-frontend-style');
		}

	
	add_action( 'wp_enqueue_scripts', 'cr_organization_catalog_frontend' );