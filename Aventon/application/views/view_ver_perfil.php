<div class="col-sm-8 text-center"> 


    <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
    <h1 class="h10 mb-3 font-weight-normal">Datos de perfil</h1> 
    
     <?php if ($this->session->flashdata('notifico')): ?>

    <p style="color:green;"><b><?php echo $this->session->flashdata('notifico') ?> </b></p>

    <?php endif; ?>
    
     <?php if ($this->session->flashdata('error')): ?>

    <p style="color:red;"><b><?php echo $this->session->flashdata('error') ?> </b></p>

    <?php endif; ?>
    <hr>
            <div class="container">    
                <table class="table table-striped">
                <thead>
                <tr>
                <th style="text-align:center;" height=45 width=100>Nombre</th>
                <th style="text-align:center;" height=45 width=100>Apellido</th>
                <th style="text-align:center;" height=45 width=100>Calificacion como chofer</th>
                <th style="text-align:center;" height=45 width=100>Calificacion como pasajero</th>
                  
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td><?php echo $this->session->flashdata('nom'); ?></td>
                  <td><?php echo $this->session->flashdata('ap'); ?></td>
                  <td><?php echo $this->session->flashdata('calif_chofer'); ?></td>
                  <td><?php echo $this->session->flashdata('calif_pasajero'); ?></td>
                      
                </tr>
                </tbody>
                
                </table>
            </div>  


   

    <br>





</div>
