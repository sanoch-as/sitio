<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="./assets/css/main.css" rel="stylesheet">
    <link href="./assets/css/sanoch.css" rel="stylesheet">
</head>

<body>
    <div class="container">      
        <div class="row">
            
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Resultado</div>
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