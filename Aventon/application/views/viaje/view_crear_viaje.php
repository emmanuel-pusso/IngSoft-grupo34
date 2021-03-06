<div class="col-sm-8 text-left"> 
    <form id="formulario2" name="formAuto" method="post" action="<?php echo base_url(); ?>crear_viaje/crear_viaje" class="form-signin">
        <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Crear viaje</h1>


        <p>Desde</p>
        <label for="origen" class="sr-only">Origen</label> 
        <input type="text" id="origen" name="origen" class="form-control" placeholder="Origen"  required autofocus value="<?php echo $this->session->flashdata('origen'); ?>">        
        <br>        

        <p> Hasta</p>
        <label for="destino" class="sr-only">Destino</label>           
        <input type="text" id="destino" name="destino" class="form-control" placeholder="Destino"  required autofocus value="<?php echo $this->session->flashdata('destino'); ?>">        
        <br>

        <p> Fecha</p>
        <label for="fecha" class="sr-only">Fecha</label>  
        <input type="date" id= "fecha" name="fecha" class="form-control" placeholder="Fecha: dd/mm/aaaa" required autofocus 
               min="<?php $hoy = date("Y-m-d"); echo $hoy;?>"
               max="<?php echo date("Y-m-d", strtotime("+30 days")); ?>"
               value="<?php echo $this->session->flashdata('fecha'); ?>">
        <br>

        <p> Hora</p>     
        <label for="hora" class="sr-only">Hora</label>
        <input type="time" id= "hora" name="hora" class="form-control" placeholder="Hora de inicio" 
               value="<?php
               if ($this->session->flashdata('hora')) {
                   echo $this->session->flashdata('hora');
               } else {
                   echo "00:00";
               }
               ?>" >
        <br>

        <p> Duración</p>
        <label for="duracion" class="sr-only">Duracion</label>                       
        <input type="number" id="duracion" name="duracion" class="form-control" placeholder="Duracion"  required autofocus 
               min="1" value="<?php echo $this->session->flashdata('duracion'); ?>">
        <br>

        <p>Costo</p>
        <label for="costo" class="sr-only">Costo</label> 
        <div class="input-group"> 
            <span class="input-group-addon">$</span>
            <input type="number" id="costo" name="costo" class="form-control currency" placeholder="Costo"  required autofocus  min="0.00" max="10000.00" step="10.00" value="<?php echo $this->session->flashdata('costo'); ?>">
        </div> 
        <br>

        <p> Mi auto</p>                 
        <select class="form-control" name="auto" id="auto">
            <option value="">Elija un auto</option>';
            <?php foreach ($groups as $each) { ?>
                <option value="<?php echo $each->id_auto ?>" <?php $result = ($each->id_auto == $this->session->flashdata('auto')) ? "selected" : " "; echo $result;?> > <?php echo $each->marca ?> <?php echo $each->modelo ?> - <?php echo $each->num_patente ?> </option>;
            <?php } ?> 
        </select>
        <br>

        <p>Plazas disponibles</p>
        <label for="plazas" class="sr-only">Plazas</label>                       
        <input type="number" id="plazas" name="plazas" class="form-control" placeholder="Plazas"  required autofocus 
               min="1" max="10" value="<?php echo $this->session->flashdata('plazas'); ?>">

        <p><u> Repetir Viaje</u>: </p>
        <input type="radio" name="frequencia" id="unico" value="unico" checked="true" onClick="yesnoCheck();"> Solo una vez<br>
        <input type="radio" name="frequencia" id="diario" value="diario" onClick="yesnoCheck();"> Diario<br>
        <input type="radio" name="frequencia" id="semanal" value="semanal" onClick="yesnoCheck();"> Semanal

         <p id="p_fecha_hasta" style='display:none'>  <u> Repetir Hasta </u> </p>
        <label for="fecha" class="sr-only">Fecha</label>  
        <input type="date" style='display:none' id= "fecha_hasta" name="fecha_hasta" class="form-control" placeholder="Fecha: dd/mm/aaaa" autofocus 
               min="<?php $hoy = date("Y-m-d");echo $hoy;?>" max="<?php echo date("Y-m-d", strtotime("+30 days")); ?>">
        <br>

        <div id='checkdays' style='display:none'>
            <input type="checkbox"  name="days[]" value="Sunday"> Dom 
            <input type="checkbox"  name="days[]" value="Monday"> Lun 
            <input type="checkbox"  name="days[]" value="Tuesday"> Mar
            <input type="checkbox"  name="days[]" value="Wednesday"> Mie
            <input type="checkbox"  name="days[]" value="Thursday"> Jue
            <input type="checkbox"  name="days[]" value="Friday"> Vie
            <input type="checkbox"  name="days[]" value="Saturday"> Sab
        </div>
        <br>
        <?php if ($notifico): ?>
            <p> <?php echo $notifico ?> </p>

<?php endif; ?>
        <button class="btn btn-lg btn-primary btn-block btn_perfil" type="submit" onClick="return validacionViaje();">Crear viaje</button>
        <button class="btn btn-lg btn-primary btn-block btn_perfil" type="reset">Limpiar</button>

    </form>
</div>




<script>
    
    function yesnoCheck() {
    
    	if (document.getElementById('unico').checked) {
            document.getElementById('p_fecha_hasta').style.display = 'none';
            document.getElementById('fecha_hasta').style.display = 'none';
            document.getElementById('checkdays').style.display = 'none';
        }
            else if (document.getElementById('semanal').checked) {
                document.getElementById('p_fecha_hasta').style.display = 'block';
                document.getElementById('fecha_hasta').style.display = 'block';
                document.getElementById('checkdays').style.display = 'none';
            }
                else if (document.getElementById('diario').checked){
                        document.getElementById('p_fecha_hasta').style.display = 'block';
                        document.getElementById('fecha_hasta').style.display = 'block';
                        document.getElementById('checkdays').style.display = 'block';
	
                    }
    }
    
    function validacionViaje() {
        origen = document.getElementById("origen").value;
        destino = document.getElementById("destino").value;
        fecha = document.getElementById("fecha").value;
        hora = document.getElementById("hora").value;
        duracion = document.getElementById("duracion").value;
        costo = document.getElementById("costo").value;
        auto = document.getElementById("auto").value;
        plazas = document.getElementById("plazas").value;

        var expresion_regular_texto = new RegExp(/^[a-zA-Z\s0-9]*$/);

        if (origen === "" || origen.length === 0 || /^\s+$/.test(origen)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }

        if (destino === "" || destino.length === 0 || /^\s+$/.test(destino)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }
        if (fecha === "" || fecha.length === 0 || /^\s+$/.test(fecha)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }

        if (hora === "" || hora.length === 0 || /^\s+$/.test(hora)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }
        if (duracion === "" || duracion.length === 0 || /^\s+$/.test(duracion)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }

        if (costo === "" || costo.length === 0 || /^\s+$/.test(costo)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }
        if (auto === "" || auto.length === 0 || /^\s+$/.test(auto)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }

        if (plazas === "" || plazas.length === 0 || /^\s+$/.test(plazas)) {
            alert('[!] Todos los campos con son obligatorios.');
            return false;
        }
        if (!expresion_regular_texto.test(origen)) {
            alert("[!] El campo origen contiene caracteres no permitidos");
            return false;
        }
        if (!expresion_regular_texto.test(destino)) {
            alert("[!] El campo destino contiene caracteres no permitidos");
            return false;
        }
        
         if ((origen.trim()).toUpperCase() == (destino.trim()).toUpperCase()) {
            //remuevo los espacios delante y detrás
            document.getElementById("origen").value = origen.trim();
            document.getElementById("destino").value = destino.trim();
            alert("[!] Origen y Destino deben ser diferentes");
            return false;
        }

    }
    document.getElementById('#fecha').value = new Date().toDateInputValue();

</script>            