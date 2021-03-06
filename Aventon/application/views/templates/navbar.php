<nav class="navbar navbar-inverse">
    <div class="container-fluid">     
        <ul class="nav navbar-nav">
            <li class="active"><a class="logo" href="<?php echo base_url() ?>login/logueado"><img class="logo" src="https://image.ibb.co/de39Y8/Logo.jpg" alt="Aventon logo"></img></a></li>
            <li class="dropdown dropdown-1">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Mi perfil
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url() ?>ver_perfil/mi_perfil">Ver perfil</a></li>
                    <li><a href="<?php echo base_url() ?>editar_perfil/">Editar perfil</a></li>
                    <li><a href="<?php echo base_url() ?>eliminar_perfil/ver_eliminar_perfil">Eliminar perfil</a></li>
                    <li><a href="<?php echo base_url() ?>tarjeta_credito/">Tarjeta de credito</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Viajes
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url() ?>crear_viaje/">Crear viaje</a></li>
                    <li><a href="<?php echo base_url() ?>viaje/mostrar_todos">Todos los viajes</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Auto
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url() ?>auto/guardar/">Cargar auto</a></li>
                    <li><a href="<?php echo base_url() ?>auto/">Mis autos</a></li>
                </ul>
            </li> 
            
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Solicitudes Recibidas
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url() ?>solicitud_pendiente/"> Pendientes</a></li>
                    <li><a href="<?php echo base_url() ?>solicitud_aprobada/"> Aprobadas</a></li>
                </ul>
            </li> 
            
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Solicitudes Enviadas
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url() ?>solicitud_enviada/pendiente/"> Pendientes</a></li>
                    <li><a href="<?php echo base_url() ?>solicitud_enviada/aprobada/"> Aprobadas </a></li>
                    <li><a href="<?php echo base_url() ?>solicitud_enviada/rechazada/"> Rechazadas </a></li>
                </ul>
            </li> 
            
            
            
        </ul> 
        
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo base_url() ?>login/cerrar_sesion"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesion -> <?php echo $this->session->userdata('nombre'); ?> </a></li>
        </ul>
    </div>
</nav>  