<?php 
    foreach($alertas as $key => $value) {
        foreach($value as $mensaje) {
?>
    <div class="alerta alerta__<?php echo $key ?>"><?php echo $mensaje ?></div>
<?php 
        }
    }
?>