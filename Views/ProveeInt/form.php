<div class="card-body">
   <input type="text" name="id" id="id" value="<?php echo $r->id_provint  ?? '' ?>" hidden>
   <div class="row">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
         <label for="nombre_provint">Nombre</label><span class="required">*</span>
            <input autofocus type="text" class="form-control" id="nombre_provint" name="nombre_provint" placeholder="Ingrese nombre" onkeyup="mayusculas(this);">
         <span></span>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="contacto_provint">Contacto</label>
            <input autofocus type="text" class="form-control" id="contacto_provint" name="contacto_provint" placeholder="Ingrese nombre de contacto" onkeyup="mayusculas(this);">
         <span></span>
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="email_provint">Email</label>
            <input autofocus type="email" class="form-control" id="email_provint" name="email_provint" placeholder="Ingrese email" onkeyup="minisculas(this);">
         <span></span>
      </div>
      <div class="form-group col-md-4 col-sm-4 col-xs-12">
         <label for="telf_provint">Teléfono</label>
            <input autofocus type="text" class="form-control" id="telf_provint" name="telf_provint" placeholder="+XXX (XXX) XXX-XX-XX"  >
         <span></span>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-md-12 col-sm-12 col-xs-12">
         <label for="dir_provint" class="form-label">Dirección</label>
         <textarea autofocus class="form-control" id="dir_provint" name="dir_provint" rows="3" placeholder="Ingrese dirección"></textarea>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-md-4 col-md-4 col-sm-4 col-xs-12">
            <label for="status">Estado </label>
            <select autofocus class="form-control custom-select rounded-0 " id="status" name="status" >
        </select>
    </div>
   </div>
</div>