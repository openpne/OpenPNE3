<?php 
include_customizes($parts_option['id'], 'before'); 
include_customizes($parts_option['id'], 'top');
include_partial('global/parts'.ucfirst($parts_name), $sf_data->getRaw('parts_option'));
include_customizes($parts_option['id'], 'bottom');
include_customizes($parts_option['id'], 'after')
 ?>
