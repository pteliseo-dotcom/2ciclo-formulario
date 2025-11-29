<?php
// CONFIGURACIÓN BASE DE DATOS
$conn = new mysqli("localhost", "root", "", "practica");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

$mensaje = '';

// --- POST: ACTUALIZAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $id = intval($_POST['id']);
    $nom = $_POST['nomproveedor'];
    $nombre = $_POST['nombrecontacto'];
    $apellido = $_POST['apellidocontacto'];
    $email = $_POST['email'];
    $tel = $_POST['telefono'];
    $dir = $_POST['direccion'];
    $ciu = $_POST['ciudad'];
    $prov = $_POST['provincia'];
    $cp = $_POST['codigopostal'];

    $stmt = $conn->prepare("UPDATE proveedores SET nomproveedor=?, nombrecontacto=?, apellidocontacto=?, email=?, telefono=?, direccion=?, ciudad=?, provincia=?, codigopostal=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $nom, $nombre, $apellido, $email, $tel, $dir, $ciu, $prov, $cp, $id);
    $stmt->execute();
    $stmt->close();
}

// --- POST: ELIMINAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM proveedores WHERE id=$id");
}

// --- LISTADO ---
$res = $conn->query("SELECT * FROM proveedores ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Proveedores</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f5f7fa;margin:0;padding:20px;}
h2{text-align:center;margin:10px 0;color:#2d3436;}
table{width:95%;margin:auto;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
th,td{padding:10px;text-align:center;border-bottom:1px solid #eee;}
th{background:#5c94e7;color:white;text-transform:uppercase;font-size:14px;}
tr:nth-child(even){background-color:#f8f9fc;}
tr:hover{background-color:#e9f0ff;}
.btn{padding:6px 12px;border:none;border-radius:5px;cursor:pointer;color:white;font-size:13px;}
.editar{background-color:#28a745;} .editar:hover{background-color:#218838;}
.eliminar{background-color:#dc3545;} .eliminar:hover{background-color:#c82333;}
.alert{width:90%;margin:10px auto;padding:10px;background:#eaf2ff;border-left:4px solid #4f86ff;color:#163256;border-radius:6px;text-align:center;}
.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;}
.modal.show{display:flex;}
.card{background:#fff;padding:20px;width:400px;border-radius:10px;}
.card input{width:100%;margin:6px 0;padding:6px;border:1px solid #ddd;border-radius:6px;}
.card button{margin-top:10px;}
</style>
</head>
<body>

<h2>Registro de Proveedores</h2>

<?php if($mensaje): ?>
<div class="alert"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<table>
<tr>
<th>ID</th><th>Proveedor</th><th>Nombre</th><th>Apellido</th><th>Email</th>
<th>Teléfono</th><th>Dirección</th><th>Ciudad</th><th>Provincia</th><th>C.P.</th><th>Acciones</th>
</tr>

<?php while($r = $res->fetch_assoc()): ?>
<tr data-row='<?= json_encode($r, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'>
<td><?= $r['id'] ?></td>
<td><?= htmlspecialchars($r['nomproveedor']) ?></td>
<td><?= htmlspecialchars($r['nombrecontacto']) ?></td>
<td><?= htmlspecialchars($r['apellidocontacto']) ?></td>
<td><?= htmlspecialchars($r['email']) ?></td>
<td><?= htmlspecialchars($r['telefono']) ?></td>
<td><?= htmlspecialchars($r['direccion']) ?></td>
<td><?= htmlspecialchars($r['ciudad']) ?></td>
<td><?= htmlspecialchars($r['provincia']) ?></td>
<td><?= htmlspecialchars($r['codigopostal']) ?></td>
<td>
  <button class="btn editar" onclick="abrirModal(this)">Editar</button>
  <form method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar este proveedor?');">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= $r['id'] ?>">
    <button type="submit" class="btn eliminar">Eliminar</button>
  </form>
</td>
</tr>
<?php endwhile; ?>
</table>

<!-- Modal editar -->
<div id="modalEdit" class="modal">
  <div class="card">
    <h3>Editar proveedor</h3>
    <form method="post">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" id="id">
      <label>Proveedor</label>
      <input type="text" name="nomproveedor" id="nomproveedor" required>
      <label>Nombre</label>
      <input type="text" name="nombrecontacto" id="nombrecontacto" required>
      <label>Apellido</label>
      <input type="text" name="apellidocontacto" id="apellidocontacto">
      <label>Email</label>
      <input type="email" name="email" id="email">
      <label>Teléfono</label>
      <input type="text" name="telefono" id="telefono">
      <label>Dirección</label>
      <input type="text" name="direccion" id="direccion">
      <label>Ciudad</label>
      <input type="text" name="ciudad" id="ciudad">
      <label>Provincia</label>
      <input type="text" name="provincia" id="provincia">
      <label>C.P.</label>
      <input type="text" name="codigopostal" id="codigopostal">
      <button class="btn editar" type="submit">Guardar</button>
      <button type="button" class="btn eliminar" onclick="cerrarModal()">Cancelar</button>
    </form>
  </div>
</div>

<script>
function abrirModal(btn){
  const tr = btn.closest('tr');
  const data = JSON.parse(tr.dataset.row);
  for (const k in data){
    const input = document.getElementById(k);
    if (input) input.value = data[k] || '';
  }
  document.getElementById('modalEdit').classList.add('show');
}
function cerrarModal(){
  document.getElementById('modalEdit').classList.remove('show');
}
document.addEventListener('keydown', e => { if(e.key==='Escape') cerrarModal(); });
</script>
</body>
</html>
<?php $conn->close(); ?>








