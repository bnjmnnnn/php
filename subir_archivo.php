<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivo</title>
</head>
<body>
    <h1>Subir Archivo de Trabajadores</h1>
    <form action="procesar_archivo.php" method="post" enctype="multipart/form-data">
        <label for="archivo">Seleccione un archivo TXT:</label>
        <input type="file" name="archivo" id="archivo" accept=".txt" required><br><br>
        <button type="submit">Subir y Procesar</button>
    </form>
</body>
</html>
 
