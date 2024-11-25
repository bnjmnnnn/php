<?php
// Verificar si se subió un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['archivo']['name'];
    $rutaTemporal = $_FILES['archivo']['tmp_name'];

    // Verificar que el archivo sea .txt
    $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    if ($ext !== 'txt') {
        die("Error: Solo se permiten archivos TXT.");
    }

    // Leer el archivo .txt
    if (($archivo = fopen($rutaTemporal, "r")) !== false) {
        echo "<h2>Resultados de la Liquidación de Sueldo</h2>";
        echo "<table border='1'>";
        echo "<tr>
                <th>Cargo</th>
                <th>Nombre</th>
                <th>RUT</th>
                <th>Sueldo Base</th>
                <th>Bonos</th>
                <th>Gratificaciones</th>
                <th>Descuento AFP</th>
                <th>Descuento Salud</th>
                <th>Sueldo Líquido</th>
              </tr>";

        // Configuración de descuentos
        $afpDescuentos = [
            "Habitat" => 0.1127, "Capital" => 0.1038, "Cuprum" => 0.1144, "Modelo" => 0.1058, "Planvital" => 0.1116, "Provida" => 0.1145, "Uno" => 0.1049
        ];
        $saludDescuentos = [
            "Fonasa_A" => 0.07,
            "Fonasa_B" => 0.07,
            "Fonasa_C" => 0.07,
            "Fonasa_D" => 0.07,
            "Banmedica" => 0.07,
            "Colmena" => 0.07,
            "Consalud" => 0.07,
            "Cruz Blanca" => 0.07,
            "MasVida" => 0.07,
            "VidaTres" => 0.07,
            "Nueva Masvida" => 0.07
        ];
        

        // Leer línea por línea
        $primeraLinea = true;
        while (($datos = fgets($archivo)) !== false) {
            // Si la primera línea es el encabezado, la saltamos
            if ($primeraLinea) {
                $primeraLinea = false;
                continue;
            }

            // Separar los datos utilizando una coma como delimitador
            $datos = explode(",", $datos);

            // Verificar que la línea tenga la cantidad correcta de columnas
            if (count($datos) < 9) {
                continue; // Saltar filas incorrectas
            }

            // Asignar datos a variables
            list($cargo, $rut, $nombre, $afp, $salud, $bonos, $gratificaciones, $sueldoBase, $otros) = $datos;

            // Calcular sueldo imponible
            $sueldoImponible = (float)$sueldoBase + (float)$bonos + (float)$gratificaciones;

            // Calcular descuentos
            $descuentoAFP = isset($afpDescuentos[$afp]) ? $sueldoImponible * $afpDescuentos[$afp] : 0;
            $descuentoSalud = isset($saludDescuentos[$salud]) ? $sueldoImponible * $saludDescuentos[$salud] : 0;

            // Calcular sueldo líquido
            $sueldoLiquido = $sueldoImponible - $descuentoAFP - $descuentoSalud;

            // Mostrar los resultados en la tabla
            echo "<tr>
                    <td>$cargo</td>
                    <td>$nombre</td>
                    <td>$rut</td>
                    <td>$sueldoBase</td>
                    <td>$bonos</td>
                    <td>$gratificaciones</td>
                    <td>$descuentoAFP</td>
                    <td>$descuentoSalud</td>
                    <td>$sueldoLiquido</td>
                  </tr>";
        }
        fclose($archivo);

        echo "</table>";
    } else {
        echo "Error al abrir el archivo.";
    }
} else {
    echo "Error al subir el archivo.";
}
?>
