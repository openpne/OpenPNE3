<?php 
include_customizes($id, 'before'); 
include_customizes($id, 'top');
echo $sf_data->getRaw('op_content');
include_customizes($id, 'bottom');
include_customizes($id, 'after')
 ?>
