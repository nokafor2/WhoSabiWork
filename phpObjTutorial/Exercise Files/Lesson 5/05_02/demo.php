<?php

/**
 * Define autoloader.
 * @param string $class_name
 */
/* function __autoload($class_name) {
  include 'class.' . $class_name . '.inc';
} */

require 'class.Address.inc';
require 'class.Database.inc';
require 'class.AddressResidence.inc';
require 'class.AddressBusiness.inc';

echo '<h2>Instantiating AddressResidence</h2>';
$address_residence = new AddressResidence();

echo '<h2>Setting properties...</h2>';
$address_residence->street_address_1 = '555 Fake Street';
$address_residence->city_name = 'Townsville';
$address_residence->subdivision_name = 'State';
$address_residence->country_name = 'United States of America';
echo $address_residence->display();
echo '<tt><pre>' . var_export($address_residence, TRUE) . '</pre></tt>';

echo '<h2>Testing Address __construct with an array</h2>';
$address_business = new AddressBusiness(array(
  'street_address_1' => '123 Phony Ave',
  'city_name' => 'Villageland',
  'subdivision_name' => 'Region',
  'country_name' => 'Canada',
));
echo $address_business;
echo '<tt><pre>' . var_export($address_business, TRUE) . '</pre></tt>';
