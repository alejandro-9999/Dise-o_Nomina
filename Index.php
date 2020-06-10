<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="#" />      
    <link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css">  
    <link rel="stylesheet" href="estilos.css">
    <title>Nomina</title>
</head>
<body>
    
    <header style="height: 70px">
     </header> 
    <?php

require_once 'dompdf/autoload.inc.php';

use Dompdf\Adapter\CPDF;      
                use Dompdf\Dompdf;
                use Dompdf\Exception;
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
<div style="height: 30px;"></div>
<div class="container">
    <div class="row">
    <div class="col-lg-12">
    <div class="card shadow-lg p-3 mb-5 bg-white ">
    <div class="card-header">Nomina mensual </div>
    <div class="card-body">    
<form  method="POST" class="needs-validation" novalidate >
    
    <?php
        
        if(!isset($_SESSION['Salario_minimo']))
        {
            ECHO '
            <h3>Valores de referencia</h3>
            <div class="form-row">
                
                <div class="col-md-4 mb-3" >
                    <label for="Salario_minimo">Salario minimo</label>
                    <input type="number" id="Salario_minimo" name="Salario_minimo" class="form-control" require >
                </div>
                <div class="col-md-4 mb-3" >
                    <label for="Auxilio_Transporte">Auxilio de transporte</label>
                    <input type="number" id="Auxilio_Transporte" name="Auxilio_Transporte"  class="form-control" require >
                </div>
        
                <div class="col-md-4 mb-3" >       
                    <label for="Exoneracion">¿Está exonerado del pago de aportes?</label>
                    <select name="Exoneracion" id="Exoneracion" class="form-control">
                        <option value=Si>Si</option>
                        <option value=No>No</option>
                    </select>
                </div> 
               
            </div>
            
                <button type="submit" name="Agregar" class="btn btn-primary"> Actualizar </button>   
                
           '

           ;
        }
        else{
           ECHO'
           <h3>Valores de referencia</h3>
            <div class="form-row">
                <div class="col-md-4 mb-3"  >
                    <label for="Salario_minimo">Salario minimo</label>
                    <input type="text" id="Salario_minimo" name="Salario_minimo" value = "'.$_SESSION['Salario_minimo'].'" class="form-control" disabled>
                </div>
                <div class="col-md-4 mb-3"  >
                    <label for="Auxilio_Transporte">Auxilio de transporte</label>
                    <input type="text" id="Auxilio_Transporte" name="Auxilio_Transporte" value ="'.$_SESSION['Auxilio_Transporte'].'" class="form-control" disabled>
                </div>
                <div class="col-md-4 mb-3"  >
                    <label for="Exoneracion">¿Está exonerado del pago de aportes?</label>
                    <select name="Exoneracion" id="Exoneracion"  disabled class="form-control" >
                        <option value=1>'.$_SESSION['Exoneracion'].'</option>
                        
                    </select>
                 </div>
            </div>';     
        }
     ?>   
    <h3>Ingreso empleado</h3>
    <div class="form-row">

        <div class="col-md-4 mb-3"  >
            <label for="Id_Empleado">Identificacion del empleado</label>
            <input type="text" id="Id_Empleado" name="Id_Empleado"  class="form-control" require>
        </div>
        <div class="col-md-4 mb-3"  >
            <label for="Nombre_Empleado">Nombre del empleado</label>
            <input type="text" id="Nombre_Empleado" name="Nombre_Empleado" class="form-control" require>
        </div>
        <div class="col-md-4 mb-3"  >
            <label for="Salario_Empleado">Salario Basico</label>
            <input type="number" id="Salario_Empleado" name="Salario_Empleado" class="form-control" require>
        </div>    
    </div>
    <div class="form-row">    
        <div class="col-md-4 mb-3"  >
            <label for="Dias_liquidados">Dias liquidados</label>
            <input type="number" id= "Dias_liquidados" name="Dias_liquidados" max="30" min = "1" class="form-control" require>
        </div>
        <div class="col-md-4 mb-3"  >
            <label for="Horas_diurnas">Horas extras diurnas</label>
            <input type="number" id= "Horas_diurnas" name="Horas_diurnas" max="48" min = "0" class="form-control" require>
        </div>
        <div class="col-md-4 mb-3"  >
            <label for="Horas_nocturnas">Horas extras nocturnas</label>
            <input type="number" id= "Horas_nocturnas" name="Horas_nocturnas" max="48" min = "0" class="form-control" require>
        </div>
    </div>
    <div class="form-row">  
        <div class="col-md-4 mb-3"  >  
            <label for="Horas_dominicales">Horas extras dominicales</label>
            <input type="number" id= "Horas_dominicales" name="Horas_dominicales" max="48" min = "0" class="form-control" require>
        </div>
    </div>        
        <button type="submit" name="Insertar" class="btn btn-primary" > Insertar </button>
    
          
          
    <button type="submit" name="Reiniciar" class="btn btn-danger"> Reiniciar </button> 
        

</div>   
    </div>
            </div>       
        </div>                  
    </div>
<div>
<div style="height: 50px;"></div>  
<div class="container">
    <div class="row">
    <div class="col-lg-12">
    <div class="card shadow-lg p-3 mb-5 bg-white ">
    <div class="card-header">Nomina mensual </div>
    <div class="card-body"> 
<style>
    th{
        text-align: center;
    }
    td{
        text-align: right;
    }
</style>
<?php

$Salida_pdf = '
        
<table  valign="middle" border="1" cellspacing="0" cellpadding="0" style="font-size:13px" style="text-align:center;" align="center" >
            <thead>
                <tr>
                    <th  rowspan="2"  >Identificacion</th>
                    <th  rowspan="2"  >Nombre </th>
                    <th  colspan="8"  >Devengado</th>
                    <th  colspan="5" > Deducciones </th>
                    <th  rowspan="2" >Neto pagado</th>
                    
                </tr>
                <tr>
                    <th style="text-align:center;">Salario básico</td>
                    <th >Días liquidados</td>
                    <th >Salario devengado</td>
                    <th >Horas extras</td>
                    <th >Recargos nocturnos</td>
                    <th >Trabajo dominical y festivo</td>
                    <th >Auxilio de transporte</td>
                    <th >Total devengado</td>
                    <th >Salud</td>
                    <th >Pensión</td>
                    <th >Fondo de solidaridad</td>
                    <th >Retencion de fuente</td>
                    <th >Otras deducciones</td>
                    
                </tr>
            </thead>';
            ECHO $Salida_pdf;
            
            
 ?>               
            <?php
            
                if(!isset($_SESSION['Salario_minimo']))
                {
                                     
                }
                else{
                    $tablas = "";
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
                        </tr>';
                        $tablas=$tablas.'<tr>
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
                        </tr>'; 
                    }
                   $Salida_pdf = $Salida_pdf.$tablas.'</table> ';


                }
               
            ?>
        </table> 
        <div style="height: 50px;"></div>
                
                <button type="submit" name="Imprimir" class="btn btn-success" > Imprimir </button> 
                
                </form>
                
                               
             
                <?php 
                    $_SESSION['variable'] = $Salida_pdf;  
                ?>
                <a href="./generar_pdf.php?ht=<?php $Salida_pdf; ?>">contactarme </a>
                
</div>
</div>
</div>
</div>
</div>
</div>    
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>