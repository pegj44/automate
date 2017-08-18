<?php

include 'config.php';

$GLOBALS['field'] = array();

function add_field($name, $attrs) {

    global $fields;

    $fields[$name] = $attrs;
}

function form_fields() {

    global $fields;

    $out = '';
    $attr = '';

    $form_field['input'] = type_input();
    $form_field['textarea'] = type_textarea();
    $form_field['select'] = type_select();

    if( !empty($fields) ) {

        foreach ($fields as $name => $field) {
            
            if( isset($form_field[$field['field_type']]) ) {

                if( !empty($field['attr']) ) {
                    foreach ($field['attr'] as $attKey => $attVal) {
                        $attr .= $attKey .'="'. $attVal .'" ';
                    }
                }

                $out .= preg_replace(
                        array('/%type%/', '/%name%/', '/%placeholder%/', '/%value%/', '/%attr%/'), 
                        array($field['type'], $name, $field['placeholder'], $field['value'], $attr), 
                        $form_field[$field['field_type']]
                    );
            }

        }

    }

    echo $out;
}

function type_input() {
    return '<p><input type="%type%" name="%name%" id="%name%" placeholder="%placeholder%" value="%value%" class="form-control" %attr%/></p>';
}

function type_textarea() {
    return '<p><textarea name="%name%" id="%id%" class="form-control">%value%</textarea></p>';
}

function type_select() {

}

function scrape_table() {
    global $scrape;

?>

    <table id="result" class="table data-list">
        <thead>
            <?php 
                foreach( $scrape as $data ) :            
                    echo '<th>'. $data[0] .'</th>';
                endforeach; 
           ?>
        </thead>
        <tbody>
            
        </tbody>
    </table>

<?php    
}

function scrape_field($scrape) {

    return $scrape;
}