<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Calendario de Turnos</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/moment.min.js"></script>
        <!-- full calendar -->
        <link rel="stylesheet" href="css/fullcalendar.min.css">
        <script src="js/fullcalendar.min.js"></script>
        <script src="js/es.js"></script>
   
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col"></div>
                <div class="col-7"><div id="CalendarioWeb"></div></div>
                <div class="col"></div>
            </div>
        </div>
        
        
    <script>
        $(document).ready(function(){
            $('#CalendarioWeb').fullCalendar({
                header:{
                    left:'today, prev,next, Miboton',
                    center:'title',
                    right:'month, basicWeek, basicDay, agendaWeek, agendaDay'
                },
                //customButtons:{
                   // Miboton:{
                     //   text:"Boton 1",
                       // click:function(){
                         //   alert("Accion del boton");
                      //  }
                        
                  //  }
            //    },
                dayClick:function(date,jsEvent,view){
                    $('#txtFecha').val(date.format());
                    $("#ModalEventos").modal();
                    
                },
               
                     events:'http://localhost/Veterinaria/version 5/Calendarioweb/eventos.php',
                    
                eventClick:function(calEvent,jsEvent,view){
                    //titulo del evento
                    $('#tituloEvento').html(calEvent.title);
                    //muestra la informacion en los inputs
                    $('#txtDescripcion').val(calEvent.descripcion);
                    $('#txtID').val(calEvent.id);
                    $('#txtTitulo').val(calEvent.title);
                    $('#txtColor').val(calEvent.color);
                    //divide la fecha recibida de la base de datos y la divide en 2, 
                    FechaHora= calEvent.start._i.split(" ");
                    $('#txtFecha').val(FechaHora[0]);
                    //$('#txtHora').val(FechaHora[1]);
                    
                    
                    $("#ModalEventos").modal();
                },
                //para arrastrar los eventos
                //no anda, ver video 19
                editable:false,
                eventDrop:function(calEvent){
                    $('#txtID').val(calEvent.id);
                     $('#txtTitulo').val(calEvent.title);
                    $('#txtColor').val(calEvent.color);
                    $('#txtDescripcion').val(calEvent.descripcion);
                    
                    var fechaHora=calEvent.start.format().split("T");
                    $('#txtFecha').val(FechaHora[0]);
                    $('#txtHora').val(FechaHora[1]);
                    
                      RecolectarDatosGUI();
                      EnviarInformacion('modificar',NuevoEvento,true);
                }
               
            });
        });
        
    </script>
    
  
    
          <!-- Modal para agregar, modificar y eliminar -->
<div class="modal fade" id="ModalEventos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloEvento"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
          <input type="hidden" id="txtID" name="txtID">
          
          Fecha: <input type="text" id="txtFecha" name="txtFecha" />
          
          <div class="form-row">
              <div class="form-group col-md-9">
                  <label>Titulo</label>
                    <input type="text" id="txtTitulo" class="form-control" placeholder="Titulo del evento">
              </div>
                <div class="form-group col-md-3">
                    <label>Hora</label>
                <input type="text" id="txtHora" value="10:30" class="form-control"/>
              </div>
          
          </div>
          
          <div class="form-group">
              <label>Descripción</label>
           <textarea id="txtDescripcion" rows="3" class="form-control"></textarea> 
          </div>
           <div class="form-group">
               <label>Color</label>
           <input type="color" value="#ff0000" id="txtColor" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnAgregar" class="btn btn-success">Agregar</button>
        <button type="button" id="btnModificar" class="btn btn-success">Modificar</button>
        <button type="button" id="btnEliminar" class="btn btn-danger" >Eliminar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
        
<!--script para guardar evento-->
<script>
    var NuevoEvento;
    
   
    $('#btnAgregar').click(function(){
         RecolectarDatosGUI();
    //$('#CalendarioWeb').fullCalendar('renderEvent',NuevoEvento );
        //
    EnviarInformacion('agregar', NuevoEvento);
    });   
    
    $('#btnEliminar').click(function(){
         RecolectarDatosGUI();
    EnviarInformacion('eliminar', NuevoEvento);
    });  
    
        $('#btnModificar').click(function(){
         RecolectarDatosGUI();
    EnviarInformacion('modificar', NuevoEvento);
    });  
    
function RecolectarDatosGUI(){
    NuevoEvento= {
        //lo de la izquierda deben coincidir con lo que guarda la base de datos
        id:$('#txtID').val(),
        title:$('#txtTitulo').val(),
        start:$('#txtFecha').val()+" "+$('#txtHora').val(),
        color:$('#txtColor').val(),
        descripcion:$('#txtDescripcion').val(),
        textColor:"#FFFFFF",
         end:$('#txtFecha').val()+" "+$('#txtHora').val()
    };
}
    
function EnviarInformacion(accion,objEvento,modal){
    //ajax sirve para enviar la informacion sin necesidad de que la pagina se refresque
        $.ajax({
            type:'POST',
            url:'eventos.php?accion='+accion,
            //envia el objeto evento
            data:objEvento,
            //funcion que trae la respuesta 
            success:function(msg){
                //si hay mensaje -> se refresca la pagina
                if(msg){
                    $('#CalendarioWeb').fullCalendar('refetchEvents');
                    
                    if(!modal){
                        //oculta modal
                    $("#ModalEventos").modal('toggle');
                    }
                    
                }
            },
            error:function(){
                alert("Se ha producido un error");
            }
            
        });
        
        }
    
</script>
    
    </body>
</html>