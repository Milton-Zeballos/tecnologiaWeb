<div class="form-group">
    <label for="nombre">Nombre</label>
    <input type="text" class="form-control" id="nombre" value="{{ old('nombre', isset($cliente) ? $cliente->nombre : null) }}" name="nombre" required>
</div>
<div class="form-group">
    <label for="correo">Correo</label>
    <input type="email" class="form-control" id="correo" value="{{ old('correo', isset($cliente) ? $cliente->correo : null) }}" name="correo" required>
</div> 
<div class="form-group">
    <label for="direccion">Dirección</label>
    <input type="text" class="form-control" id="direccion" value="{{ old('direccion', isset($cliente) ? $cliente->direccion : null) }}" name="direccion" required>
</div>