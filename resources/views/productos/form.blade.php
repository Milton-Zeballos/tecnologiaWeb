<div class="form-group">
    <label for="nombre">Nombre</label>
    <input type="text" class="form-control" id="nombre" value="{{ old('nombre', isset($producto) ? $producto->nombre : null) }}" name="nombre" required>
</div>
<div class="form-group">
    <label for="precio">Precio</label>
    <input type="number" class="form-control" id="precio" value="{{ old('precio', isset($producto) ? $producto->precio : null) }}" name="precio" step="0.01" required>
</div> 
<div class="form-group">
    <label for="categoria">Categoría</label>
    <input type="text" class="form-control" id="categoria" value="{{ old('categoria', isset($producto) ? $producto->categoria : null) }}" name="categoria" required>
</div>