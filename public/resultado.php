<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <title>Resultado Solicitudes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> 
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>    
    <link href="./assets/css/main.css" rel="stylesheet">
    <link href="./assets/css/sanoch.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap4.css" rel="stylesheet"> 
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap4.js"></script>

</head>

<body>
    <div class="container">      
        <div class="row">
            
            <div class="col-md-12">
                <div class="panel panel-primary">                    
                    <div class="panel-body">
                        <?php  
                        
                        if ($_GET['result']) {
                            if($_GET['result']=='OK'){
                                echo "<div class='alert alert-success'>Acción completada correctamente.</div>";
                            }
                            if($_GET['result']=='NOK'){
                                echo "<div class='alert alert-danger'>Error al realizar la acción.<br>".$_GET['result']."</div>";
                            }
                            if($_GET['result']=='0'){
                                echo "<div class='alert alert-danger'>Error al realizar la acción.<br>result=".$_GET['result']."<br>Mensaje:".$_GET['msg']."</div>";
                            }
                            else{
                                echo "<div class='alert alert-danger'>Error al realizar la acción.<br>result=".$_GET['result']."<br>Mensaje:".$_GET['msg']."</div>";
                            }
                            
                        }
                        else{
                            echo "<div class='alert alert-danger'>Error al realizar la acción, No se recibe result .<br></div>";
                        }                            
                        ?>                                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
</body>

</html>