<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de multiplicar</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <link rel="stylesheet" href="./css/table.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="contenedor">
        <h4>Biblioteca - Registro de libros</h4>
        <form method="POST" action="./index.php">
            <?php
                session_start();
                class Book {
                    public $author;
                    public $title;
                    public $indice;
                    public $place;
                    public $year;
                    public $pages;
                    public $editorial;
                    public $notes;
                    public $isbn;
                    private $error;

                    function __construct ($arr) {
                        $this->author = $arr["author"];
                        $this->title = $arr["title"];
                        $this->indice = $arr["indice"];
                        $this->place = $arr["place"];
                        $this->year = $arr["year"];
                        $this->pages = $arr["pages"];
                        $this->editorial=$arr["editorial"];
                        $this->notes = $arr["notes"];
                        $this->isbn = $arr["isbn"];
                        $this->error="";
                    }

                    function getError() {
                        return $this->error;
                    }

                    function addError($e) {
                        $this->error.=$e;
                    }
                    function validate(){
                        unset($_SESSION["error"]);
                        if(isset($this->author)) {
                            if(!preg_match("/^([A-Za-z]| )+,(| )([A-Za-z]| )+$/",$this->author)) {
                                $this->error.="<div><strong>Error!</strong> ingrese correctamente el autor [Apellidos, Nombres]</div>";
                            }
                            if(!isset($this->title) || !preg_match("/^([A-Za-z]| )+$/",$this->title)) {
                                $this->error.="<div><strong>Error!</strong> ingrese correctamente el título</div>";
                            }
                            if(!isset($this->indice) || !preg_match("/^\d+$/",$this->indice)) {
                                $this->error.="<div><strong>Error!</strong> El índice debe ser un número entero</div>";
                            }
                            if(!isset($this->place) || !preg_match("/^([A-Za-z]| )+$/",$this->place)) {
                                $this->error.="<div><strong>Error!</strong> Ingrese el lugar donde fue publicado el libro</div>";
                            }
                            if(!isset($this->year) || !preg_match("/^\d+$/",$this->year) || $this->year > 2022) {
                                $this->error.="<div><strong>Error!</strong> Ingrese un año válido</div>";
                            }
                            if(!isset($this->pages) || !preg_match("/^\d+$/",$this->pages) && $this->pages < 0 ) {
                                $this->error.="<div><strong>Error!</strong> Ingrese una cantidad de páginas válidas</div>";
                            }
                            if(!isset($this->editorial) || !preg_match("/^[\w| ]+$/",$this->editorial)) {
                                $this->error.="<div><strong>Error!</strong> Ingrese una editorial correcta</div>";
                            }
                            if(isset($this->notes) && !preg_match("/^(\w|\d| |\.|,)+$/",$this->notes)) {
                                $this->error.="<div><strong>Error!</strong> Las notas solo admiten texto</div>";
                            }
                            if(!isset($this->isbn) || !preg_match("/^[\d]{13}$/",$this->isbn)) {
                                $this->error.="<div><strong>Error!</strong> Ingrese el ISBN del libro en el formato correcto 13 dígitos</div>";
                            }
                            return $this->error;
                        }
                    }


                }
                if(sizeof($_POST)> 1) {
                    $book = new Book($_POST);
                    foreach ($_SESSION['books'] as $b){
                        if($b->isbn === $book->isbn) {
                            $book->addError("<div><strong>Error!</strong> ISBN del libro está duplicado </div>");
                        }
                    }
                    if($book->validate() && strlen($book->getError()) > 0){
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        echo $book->getError();
                        $asdf=<<<EOF
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        EOF;
                        echo $asdf;
                        echo '</div>';
                    }else {
                        if(!isset($_SESSION['books'])) $_SESSION['books'] = array();
                        array_push($_SESSION['books'], $book);
                    }
                }

            ?>
            <div class="form_campos">
                <div class="d-flex">
                <section>
                    <input name="author" class="control"  placeholder="[Apellidos, Nombres]"  />
                    <label for="author" class="control_label label_correo">Autor: </label>
                </section>
                <section>
                    <input name="title" class="control" placeholder="Titulo del libro"  />
                    <label for="title" class="control_label label_correo">Título: </label>
                </section>
                </div>
                <div class="d-flex mt-1">
                <section>
                    <input type="number" name="indice" class="control" step="1" placeholder="Ej: 1"  />
                    <label for="indice" class="control_label label_correo">Número de Índice: </label>
                </section>
                <section>
                    <input  name="place" class="control" placeholder="(Ciudad en la que se publico el libro)"  />
                    <label for="place" class="control_label label_correo">Lugar de publicación: </label>
                </section>
                </div>
                <div class="d-flex mt-1">
                <section>
                    <input type="number" name="year" class="control" step="1" placeholder="Ej: 1980"  />
                    <label for="year" class="control_label label_correo">Año de edicion: </label>
                </section>
                <section>
                    <input type="number" name="pages" class="control" step="1" placeholder="Ej: 300"  />
                    <label for="pages" class="control_label label_correo">Número de páginas: </label>
                </section>
                </div>
                <div class="d-flex mt-1">
                <section>

                    <input name="editorial" class="control" placeholder="Ej: Editorial Errata Naturae"  />
                    <label for="editorial" class="control_label label_correo">Editorial: </label>
                </section>
                <section>
                    <input name="isbn" class="control" step="1" placeholder="Ej: 0785650532152"  />
                    <label for="isbn" class="control_label label_correo">ISBN: </label>
                </section>
                </div>
                <div class="d-flex mt-1">
                    <section>
                    <textarea name="notes" class="control" placeholder="Notas extras..." rows="1"  ></textarea>
                    <label for="notes" class="control_label label_correo">Notas: </label>
                    </section>
                </div>
                <button name="ingresar" type="submit">
                    Guardar
                </button>
            </div>
        </form>
    </div>
    <main>
 <div id="wrapper">
  <?php 
    if(isset($_SESSION['books'])) {
        $rows="";
        foreach ($_SESSION['books'] as $book) {
            $rows.=<<<EOF
            <tr>
                <td class="lalign">$book->author</td>
                <td>$book->title</td>
                <td>$book->indice</td>
                <td>$book->place</td>
                <td>$book->year</td>
                <td>$book->pages</td>
                <td>$book->notes</td>
                <td>$book->isbn</td>
            </tr>
            EOF;
        }
    $table = <<<EOF
        <h1>Libros agregados: </h1>
        <table id="keywords" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th><span>Autor</span></th>
            <th><span>Título</span></th>
            <th><span>Índice</span></th>
            <th><span>Lugar de publicación:</span></th>
            <th><span>Año de edición</span></th>
            <th><span>Número de páginas:</span></th>
            <th><span>Notas:</span></th>
            <th><span>ISBN:</span></th>
          </tr>
        </thead>
        <tbody>
        $rows
        </tbody>
        </table>
    EOF;
    echo $table;
    }
  ?>
 </div> 
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.14/js/jquery.tablesorter.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://kit.fontawesome.com/0c7c28094d.js" crossorigin="anonymous"></script>
    <script>
          /* Multiple Item Picker */
  $('.selectpicker').selectpicker({
    style: 'btn-default'
  });
  $(function () {
  $("#datepicker").datepicker({ 
      autoclose: true, 
      todayHighlight: true,
      clearBtn: true
  }).datepicker('update', new Date());
});

 $("#datepicker").keyup(function(e){
   console.log("heool");
   if(e.keyCode ==8 || e.keyCode == 46) {
     $("#datepicker").datepicker('update', "");
   }
 });
var alertList = document.querySelectorAll('.alert')
var alerts =  [].slice.call(alertList).map(function (element) {
  return new bootstrap.Alert(element)
})
    </script>
</body>

</html>