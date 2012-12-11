<?php
class Entries extends AppModel {
    public $name = 'Entries'; 
    public $validate = array(
        'targetURL' => 'isUnique'
    );
    
}
?>