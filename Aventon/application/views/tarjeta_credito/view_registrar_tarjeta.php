<div class="col-sm-8 text-left"> 
    <form id="formulario2" method="post"  onsubmit="return validar();" action="<?php echo base_url();?>tarjeta_credito/crear_tarjeta" class="form-signin">
        <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Registrar tarjeta</h1>


        <p>Tipo de tarjeta</p>
        <label for="tipo" class="sr-only">Tipo de tarjeta</label> 
        <input type="text" id="tipo" name="tipo" class="form-control" placeholder="Tipo"  required autofocus value="<?php echo $this->session->flashdata('tipo'); ?>">        
        <br>        
            
        <p>Titular</p>
        <label for="titular" class="sr-only">Titular</label>           
        <input type="text" id="titular" name="titular" class="form-control" placeholder="Titular de la tarjeta"  required autofocus value="<?php echo $this->session->flashdata('titular'); ?>">        
        <br>
        
        <p>Numero de tarjeta</p>
        <label for="numero" class="sr-only">Numero de tarjeta</label> 
        <input type="text" id="numero" name="numero" class="form-control" placeholder="Numero de tarjeta"  required autofocus value="<?php echo $this->session->flashdata('numero'); ?>">        
        <br> 
        
        <p>Fecha de vencimiento</p>
        <label for="fecha" class="sr-only">Numero de tarjeta</label> 
        <input type="text" id="fecha" name="fecha" class="form-control" placeholder="MMYY"  required autofocus value="<?php echo $this->session->flashdata('fecha'); ?>">        
        <br> 
        
        <p>Codigo de seguridad</p>
        <label for="codigo" class="sr-only">Codigo de seguridad</label> 
        <input type="password" id="codigo" name="codigo" class="form-control" placeholder="Codigo de seguridad"  required autofocus value="<?php echo $this->session->flashdata('codigo'); ?>">        
        <br>


        <?php if ($error): ?>

            <p> <?php echo $error ?> </p>

        <?php endif; ?>
            
        <button class="btn btn-lg btn-primary btn-block btn_perfil" type="submit">Registrar tarjeta</button>

    </form>
</div>

<script>
    function validar() {
	tipo = document.getElementById("tipo").value; 
	titular = document.getElementById("titular").value;
	numero = document.getElementById("numero").value;
	fecha = document.getElementById("fecha").value;
	codigo = document.getElementById("codigo").value;

	var expresion_regular_onlyLetter = /^[A-Za-z\s]+$/;
	var expresion_regular_passwordLetter = /[A-Za-z]/; 
        var expresion_regular_onlyNumber = /[0-9]/;
        
        if (tipo === "" || titular === "" || numero === "" || fecha === "" || codigo === "") {
            alert("[!] Todos los campos con son obligatorios");
            return false;
	} 
	if (!expresion_regular_onlyLetter.test(tipo)) {
            alert("[!] El tipo de tarjeta contiene caracteres no permitidos");
            return false;
	}
	if (!expresion_regular_onlyLetter.test(titular)) {
            alert("[!] El titular contiene caracteres no permitidos");
            return false;
	}
        if (!expresion_regular_onlyNumber.test(numero)) {
            alert("[!] El numero de tarjeta contiene caracteres no permitidos");
            return false;
	}
        if(numero.length > 18 || numero.length < 15){
            alert("[!]El numero de tarjeta debe tener una longitud de entre 15 y 17 numeros");
            return false;
        }
        if (!expresion_regular_onlyNumber.test(codigo)) {
            alert("[!] El codigo de seguridad contiene caracteres no permitidos");
            return false;
	}
        if(codigo.length > 5 || codigo.length < 2){
            alert("[!]El codigo de seguridad debe tener una longitud de entre 3 y 4 numeros");
            return false;
        }
        if (!expresion_regular_onlyNumber.test(fecha)) {
            alert("[!] La fecha de vencimiento contiene caracteres no permitidos");
            return false;
	}
        if(fecha < 0818){
            alert("[!] La tarjeta de credito se encuentra vencida. Por favor ingrese otra");
            return false;
        }
        if(!(fecha.length === 4)){
            alert("[!]Fecha de vencimiento invalida. Recuerde que el formato es MMYY");
            return false;
        }
        


}
    
    
    
</script>
