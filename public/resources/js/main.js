var currentDate = function(){
    var date = new Date();
    return date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
    //return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate();
}
var showMessage = function(text){
    var e = "<div class='modal fade' tabindex='-1' role='dialog'> \
        <div class='modal-dialog modal-dialog-centered modal-sm' role='document'> \
        <div class='modal-content'> \
            <div class='modal-header'> \
                <h5 class='modal-title'>Mensaje de Sistema</h5> \
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'> \
                <span aria-hidden='true'>&times;</span> \
                </button> \
            </div> \
            <div class='modal-body'> \
                <p>" + text + "</p> \
            </div> \
            <div class='modal-footer'> \
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button> \
            </div> \
        </div> \
        </div> \
    </div>";

    $(e).modal({
        backdrop: false
    });
}

var cargarMarcaciones = function(rows, codigo){
    var row;
    var dialog = $("#modalVerMarcaciones");
    if(codigo == 0){
        dialog = $("#modalVerMarcacionesTodos");
    }
    var table = dialog.find("table");
    table.find("tbody").empty();
    for(i in rows){
        row = "<tr>";
        row += "<th scope=\"row\">" + (parseInt(i) + 1) +"</th>";
        if(codigo == 0){
            row += "<td scope=\"row\">" + rows[i].empleado + "</td>";
        }
        row += "<td scope=\"row\">" + rows[i].fecha_hora + "</td>";
        row += "</tr>"
        table.find("tbody").append(row);
    }
    dialog.modal('show')
}

var ver = function(codigo, option){
    if($(".radioOpciones:checked").val() == "0"){
        $(".tipoVer").html("HOY: <strong>" + currentDate() + "</strong>");
        //
        if(option == 0){
            $.getJSON("marcaciones/" + codigo + "/0", function(response){
                cargarMarcaciones(response.data, codigo)
            })
        } else if(option == 1){
            window.open($(location).attr('href') + "pdf/" + codigo + "/0");
        }
    }else if($(".radioOpciones:checked").val() == "1"){
        var del = $("#txtDel").val();
        var al = $("#txtAl").val();
        if(del == "" || al == ""){
            showMessage("Debe especificar el rango de fechas")
            return;
        }
        //
        if(del > al){
            showMessage("La decha <strong>Del</strong> no puede ser mayor a la fecha <strong>Al</strong>")
            return;
        }
        //
        $(".tipoVer").html("Del: <strong>" + del + "</strong> Al: <strong>" + al + "</strong>");
        //
        if(option == 0){
            $.getJSON("marcaciones/" + codigo + "/0/" + del + "/" + al, function(response){
                cargarMarcaciones(response.data, codigo)
            })
        } else if(option == 1){
            window.open($(location).attr('href') + "pdf/" + codigo + "/0/" + del + "/" + al);
        }
    }
}

$(document).ready(function(){

    $(".ver").click(function(){
        console.log("ver");
        
        var codigo = $(this).attr("rel").split("|")[0];
        var nombre = $(this).attr("rel").split("|")[1];

        $("#txtCodigo").val(codigo);
        
        $(".modalVerNombre").text(nombre);
        $('.radioOpciones[name="radioOpciones"]').filter('[value="0"]').prop('checked', true).trigger("click");
        $('.txtDelAl').val("");
        $('#modalVerOpciones').modal('show')
    });

    $(".navbar button.btn-primary").click(function(){
        console.log("narvar ver");

        $("#txtCodigo").val(0);
        
        $(".modalVerNombre").text("Todos");
        $('.radioOpciones[name="radioOpciones"]').filter('[value="0"]').prop('checked', true);
        $('.txtDelAl').val("");
        $('#modalVerOpciones').modal('show')
    });

    $(".navbar button.btn-primary").click(function(){
        console.log("narvar pdf");

        $("#txtCodigo").val(0);
        
        $(".modalVerNombre").text("Todos");
        $('.radioOpciones[name="radioOpciones"]').filter('[value="0"]').prop('checked', true);
        $('.txtDelAl').val("");
        $('#modalVerOpciones').modal('show')
    });

    $("#modalVerOpciones button.btn-primary").click(function(){
        ver($("#txtCodigo").val(), 0);
    });

    $("#modalVerOpciones button.btn-warning").click(function(){
        ver($("#txtCodigo").val(), 1);
    });

    $(".radioOpciones").click(function(){
        if($(".radioOpciones:checked").val() == "0"){
            $(".txtDelAl").prop("disabled", true);
        }else if($(".radioOpciones:checked").val() == "1"){
            $(".txtDelAl").prop("disabled", false);
        }
    });
})