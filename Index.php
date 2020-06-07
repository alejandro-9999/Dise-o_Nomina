<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="#" />      
    <link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css">  
    <title>Nomina</title>
</head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
  text-align: left;    
}
</style>
<body>
    <?php
        session_start();
        ob_start();
        
        if(isset($_POST['Agregar']))
        {
            if(!isset($_SESSION['Salario_minimo']))
            {
                $_SESSION['Salario_minimo'] = $_POST['Salario_minimo'];
                $_SESSION['Auxilio_Transporte'] = $_POST['Auxilio_Transporte'];
                $_SESSION['Exoneracion'] = $_POST['Exoneracion'];
                 
            }
                 
            
        }
        if(!isset($_SESSION['empleados']))
        {
            $_SESSION['empleados'] = array();
        }
        if (isset($_POST['Insertar']))
        {
            if(intval($_SESSION['Salario_minimo'])>=0)
            {
                $Salario_Minimo =  intval($_SESSION['Salario_minimo']);
                $Valor_auxilio =  intval($_SESSION['Auxilio_Transporte']);
                $id = $_POST['Id_Empleado'];
                $Nombre_e = $_POST['Nombre_Empleado'];
                $Salario_e = $_POST['Salario_Empleado'];
                $Dias_e = $_POST['Dias_liquidados'];
                $Horas_d = $_POST['Horas_diurnas'];
                $Horas_n = $_POST['Horas_nocturnas'];
                $Horas_f = $_POST['Horas_dominicales'];
                $Salario_Devengado = (intval($Salario_e)/30)*intval($Dias_e);
                $Valor_Hora = intval($Salario_e)/240;
                $Extras_Dias = intval($Horas_d) * ($Valor_Hora*1.25);
                $Extras_Noches = intval($Horas_n) * ($Valor_Hora*1.35);
                $Extras_Festivos = intval($Horas_f) * ($Valor_Hora*1.75);
                if(intval($Salario_e)<=($Salario_Minimo*2))
                {
                    $Auxilio_Transporte = ($Valor_auxilio/30)*intval($Dias_e);
                }
                else
                {
                    $Auxilio_Transporte = 0;
                }
                $Total_Devengado = $Salario_Devengado + $Extras_Dias + $Extras_Noches + $Extras_Festivos + $Auxilio_Transporte;
                $Salud = ($Total_Devengado - $Auxilio_Transporte)*0.04;
                $Pension = ($Total_Devengado - $Auxilio_Transporte)*0.04;
                $Fondo_Solidaridad = 0;
                $Retencion_Fuente = 0;
                $Otras_Deduciones = 0;
                $Total_Deduciones  = $Salud + $Pension + $Fondo_Solidaridad + $Retencion_Fuente + $Otras_Deduciones;
                $Neto_Pagado = $Total_Devengado - $Total_Deduciones ;
                $empleado =  array(
                    "id" => $id,
                    "Nombre" => $Nombre_e,
                    "Salario" => $Salario_e,
                    "Dias" => $Dias_e,
                    "Salario_Devengado" => $Salario_Devengado,
                    "Horas_extras" => round($Extras_Dias,2),
                    "Recargos_Nocturnos" => round($Extras_Noches,2),
                    "Dominical" => round($Extras_Festivos,2), 
                    "Auxilo_Transporte" => round($Auxilio_Transporte,2),
                    "Total_Devengado" => round($Total_Devengado,2),
                    "Salud" => round($Salud,2),
                    "Pension" => round($Pension,2),
                    "Fondo" => $Fondo_Solidaridad,
                    "Retencion" => $Retencion_Fuente,
                    "Otras" => $Otras_Deduciones,
                    "Neto" => round($Neto_Pagado,2)   
                );

                $_SESSION['empleados'][$id] = $empleado;
                
            
            }    
        }
        if(isset($_POST['Reiniciar']))
        {
            session_destroy();
        }
        
    ?>
<form  method="POST">
    <h1 align="center">Nomina Mensual</h1>
    <?php
        
        if(!isset($_SESSION['Salario_minimo']))
        {
            ECHO '
            <div class="card" style="width: 25rem;">
            <h2>Valores de referencia</h2>
            <div class="form-group" >
            <label for="Salario_minimo">Salario minimo</label>
            <input type="number" id="Salario_minimo" name="Salario_minimo" class="form-control" require >
            </div>
            <div class="form-group" >
            <label for="Auxilio_Transporte">Auxilio de transporte</label>
            <input type="number" id="Auxilio_Transporte" name="Auxilio_Transporte"  class="form-control" require >
            </div>
            <div class="form-group" >
            <label for="Exoneracion">¿Está exonerado del pago de aportes?</label>
            <select name="Exoneracion" id="Exoneracion" class="form-control">
                <option value=Si>Si</option>
                <option value=No>No</option>
                
           </select>
           </div>
           <button type="submit" name="Agregar" class="btn btn-success"> Actualizar </button>
           </div>
           '

           ;
        }
        else{
           ECHO'
           <h2>Valores de referencia</h2>
            <div class="form-group" >
                <label for="Salario_minimo">Salario minimo</label>
                <input type="text" id="Salario_minimo" name="Salario_minimo" value = "'.$_SESSION['Salario_minimo'].'" disabled>
            </div>
            <div class="form-group" >
                <label for="Auxilio_Transporte">Auxilio de transporte</label>
                <input type="text" id="Auxilio_Transporte" name="Auxilio_Transporte" value ="'.$_SESSION['Auxilio_Transporte'].'" disabled>
            </div>
            <div class="form-group" >
                <label for="Exoneracion">¿Está exonerado del pago de aportes?</label>
                <select name="Exoneracion" id="Exoneracion"  disabled>
                    <option value=1>'.$_SESSION['Exoneracion'].'</option>
                    
                </select>
            </div>';     
        }
     ?>   
    </div><br>
    <div   style="border-style: double;">
        <label for="Id_Empleado">Identificacion del empleado</label>
        <input type="text" id="Id_Empleado" name="Id_Empleado" require>
        <label for="Nombre_Empleado">Nombre del empleado</label>
        <input type="text" id="Nombre_Empleado" name="Nombre_Empleado" require>
        <label for="Salario_Empleado">Salario Basico</label>
        <input type="number" id="Salario_Empleado" name="Salario_Empleado" require>
        <label for="Dias_liquidados">Dias liquidados</label>
        <input type="number" id= "Dias_liquidados" name="Dias_liquidados" max="30" min = "1" require><br>
        <label for="Horas_diurnas">Horas extras diurnas</label>
        <input type="number" id= "Horas_diurnas" name="Horas_diurnas" max="48" min = "0" require><br>
        <label for="Horas_nocturnas">Horas extras nocturnas</label>
        <input type="number" id= "Horas_nocturnas" name="Horas_nocturnas" max="48" min = "0" require><br>
        <label for="Horas_dominicales">Horas extras dominicales</label>
        <input type="number" id= "Horas_dominicales" name="Horas_dominicales" max="48" min = "0" require><br>
        <button type="submit" name="Insertar"> Insetar </button>    

    </div>
    <div "border-style: double;">
        <table style="width:100%">
            <tr>
                <th rowspan="2">Identificacion</th>
                <th rowspan="2" >Nombre </th>
                <th colspan="8">Devengado</th>
                <th colspan="5">Deducciones </th>
                <th rowspan="2">Neto pagado</th>
                
            </tr>
            <tr>
                <td>Salario básico</td>
                <td>Días liquidados</td>
                <td>Salario devengado</td>
                <td>Horas extras</td>
                <td>Recargos nocturnos</td>
                <td>Trabajo dominical y festivo</td>
                <td>Auxilio de transporte</td>
                <td>Total devengado</td>
                <td>Salud</td>
                <td>Pensión</td>
                <td>Fondo de solidaridad</td>
                <td>Retencion de fuente</td>
                <td>Otras deducciones</td>
                
            </tr>
            <?php
                if(!isset($_SESSION['Salario_minimo']))
                {
                                     
                }
                else{
                    foreach($_SESSION['empleados'] as $id => $value)
                    {
                        ECHO '<tr>
                        <td>'.$value['id'].'</td>
                        <td>'.$value['Nombre'].'</td>
                        <td>'.$value['Salario'].'</td>
                        <td>'.$value['Dias'].'</td>
                        <td>'.$value['Salario_Devengado'].'</td>
                        <td>'.$value['Horas_extras'].'</td>
                        <td>'.$value['Recargos_Nocturnos'].'</td>
                        <td>'.$value['Dominical'].'</td>
                        <td>'.$value['Auxilo_Transporte'].'</td>
                        <td>'.$value['Total_Devengado'].'</td>
                        <td>'.$value['Salud'].'</td>
                        <td>'.$value['Pension'].'</td>
                        <td>'.$value['Fondo'].'</td>
                        <td>'.$value['Retencion'].'</td>
                        <td>'.$value['Otras'].'</td>
                        <td>'.$value['Neto'].'</td>    
                        <tr>';
                    }
                }
            ?>
        </table>    
    </div>       
    <button type="submit" name="Reiniciar"> Reiniciar </button> 
        
</form>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>