<?php
/** Modelo de lectura de mascotas. Adapta el esquema MySQL al formato que usan las vistas. */
class Mascota
{
    private static ?array $cache = null;

    public static function todas(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        try {
            $consulta = Database::conectar()->query(
                'SELECT
                    id_mascota,
                    id_refugio,
                    nombre,
                    especie,
                    raza,
                    edad_meses,
                    sexo,
                    tamanio,
                    vacunado,
                    esterilizado,
                    estado_salud_detalle,
                    compatibilidad_ninos,
                    compatibilidad_perros,
                    compatibilidad_gatos,
                    descripcion,
                    estado_adopcion,
                    estado_publicacion
                FROM mascotas'
            );
            $registros = $consulta->fetchAll();

            $imagenesConsulta = Database::conectar()->query(
                'SELECT id_mascota, ruta_imagen
                 FROM mascotas_imagenes
                 ORDER BY id_mascota, id_imagen'
            );
            $imagenesPorMascota = [];

            foreach ($imagenesConsulta->fetchAll() as $imagen) {
                $imagenesPorMascota[(int) $imagen['id_mascota']][] = (string) $imagen['ruta_imagen'];
            }

            self::$cache = array_map(
                static function (array $registro) use ($imagenesPorMascota): array {
                    $fotos = $imagenesPorMascota[(int) $registro['id_mascota']] ?? [];
                    $registro['fotos'] = $fotos;
                    $registro['foto'] = $fotos[0] ?? null;

                    return self::normalizarRegistro($registro);
                },
                $registros
            );

            return self::$cache;
        } catch (Throwable $error) {
            error_log('ADOPT ME - No fue posible consultar las mascotas: ' . $error->getMessage());
            self::$cache = [];
            return self::$cache;
        }
    }

    public static function publicadas(): array
    {
        return array_values(array_filter(
            self::todas(),
            static fn(array $mascota): bool => ($mascota['pub'] ?? '') === 'publicado'
        ));
    }

    public static function buscar(int $id): ?array
    {
        foreach (self::todas() as $mascota) {
            if ((int) $mascota['id'] === $id) {
                return $mascota;
            }
        }

        return null;
    }

    public static function guardar(array $datos, int $idRefugio, array $imagenes = []): ?int
    {
        $nombre = trim((string) ($datos['nombre'] ?? ''));
        $especie = trim((string) ($datos['especie'] ?? ''));
        $raza = trim((string) ($datos['raza'] ?? ''));
        $sexo = trim((string) ($datos['sexo'] ?? ''));
        $descripcion = trim((string) ($datos['descripcion'] ?? ''));

        if ($idRefugio <= 0 || $nombre === '' || $especie === '' || $raza === '' || $sexo === '' || $descripcion === '') {
            return null;
        }

        $pdo = Database::conectar();
        $pdo->beginTransaction();

        try {
            $consulta = $pdo->prepare(
                'INSERT INTO mascotas (
                    id_refugio,
                    nombre,
                    especie,
                    raza,
                    edad_meses,
                    sexo,
                    tamanio,
                    vacunado,
                    esterilizado,
                    estado_salud_detalle,
                    compatibilidad_ninos,
                    compatibilidad_perros,
                    compatibilidad_gatos,
                    descripcion,
                    estado_adopcion,
                    estado_publicacion
                ) VALUES (
                    :id_refugio,
                    :nombre,
                    :especie,
                    :raza,
                    :edad_meses,
                    :sexo,
                    :tamanio,
                    :vacunado,
                    :esterilizado,
                    :estado_salud_detalle,
                    :compatibilidad_ninos,
                    :compatibilidad_perros,
                    :compatibilidad_gatos,
                    :descripcion,
                    :estado_adopcion,
                    :estado_publicacion
                )'
            );

            $resultado = $consulta->execute([
                ':id_refugio' => $idRefugio,
                ':nombre' => $nombre,
                ':especie' => ucfirst($especie),
                ':raza' => $raza,
                ':edad_meses' => (int) ($datos['edad_meses'] ?? 0),
                ':sexo' => strtolower($sexo),
                ':tamanio' => strtolower((string) ($datos['tamanio'] ?? 'mediano')),
                ':vacunado' => !empty($datos['vacunado']) ? 1 : 0,
                ':esterilizado' => !empty($datos['esterilizado']) ? 1 : 0,
                ':estado_salud_detalle' => trim((string) ($datos['estado_salud_detalle'] ?? '')),
                ':compatibilidad_ninos' => !empty($datos['compatibilidad_ninos']) ? 1 : 0,
                ':compatibilidad_perros' => !empty($datos['compatibilidad_perros']) ? 1 : 0,
                ':compatibilidad_gatos' => !empty($datos['compatibilidad_gatos']) ? 1 : 0,
                ':descripcion' => $descripcion,
                ':estado_adopcion' => (string) ($datos['estado_adopcion'] ?? 'disponible'),
                ':estado_publicacion' => (string) ($datos['estado_publicacion'] ?? 'borrador'),
            ]);

            if (!$resultado) {
                $pdo->rollBack();
                return null;
            }

            $idMascota = (int) $pdo->lastInsertId();
            self::guardarImagenes($pdo, $idMascota, $imagenes);
            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }

        self::$cache = null;
        return $idMascota;
    }

    public static function actualizar(int $idMascota, array $datos, int $idRefugio, array $imagenes = []): bool
    {
        $pdo = Database::conectar();
        $pdo->beginTransaction();

        try {
            $propiedad = $pdo->prepare(
                'SELECT 1
                 FROM mascotas
                 WHERE id_mascota = :id_mascota AND id_refugio = :id_refugio
                 FOR UPDATE'
            );
            $propiedad->execute([
                ':id_mascota' => $idMascota,
                ':id_refugio' => $idRefugio,
            ]);

            if ($propiedad->fetchColumn() === false) {
                $pdo->rollBack();
                return false;
            }

            $consulta = $pdo->prepare(
                'UPDATE mascotas SET
                    nombre = :nombre,
                    especie = :especie,
                    raza = :raza,
                    edad_meses = :edad_meses,
                    sexo = :sexo,
                    tamanio = :tamanio,
                    vacunado = :vacunado,
                    esterilizado = :esterilizado,
                    estado_salud_detalle = :estado_salud_detalle,
                    compatibilidad_ninos = :compatibilidad_ninos,
                    compatibilidad_perros = :compatibilidad_perros,
                    compatibilidad_gatos = :compatibilidad_gatos,
                    descripcion = :descripcion,
                    estado_adopcion = :estado_adopcion,
                    estado_publicacion = :estado_publicacion,
                    fecha_actualizacion = NOW()
                 WHERE id_mascota = :id_mascota AND id_refugio = :id_refugio'
            );

            $resultado = $consulta->execute([
                ':nombre' => trim((string) ($datos['nombre'] ?? '')),
                ':especie' => ucfirst(trim((string) ($datos['especie'] ?? ''))),
                ':raza' => trim((string) ($datos['raza'] ?? '')),
                ':edad_meses' => (int) ($datos['edad_meses'] ?? 0),
                ':sexo' => strtolower(trim((string) ($datos['sexo'] ?? ''))),
                ':tamanio' => strtolower(trim((string) ($datos['tamanio'] ?? 'mediano'))),
                ':vacunado' => !empty($datos['vacunado']) ? 1 : 0,
                ':esterilizado' => !empty($datos['esterilizado']) ? 1 : 0,
                ':estado_salud_detalle' => trim((string) ($datos['estado_salud_detalle'] ?? '')),
                ':compatibilidad_ninos' => !empty($datos['compatibilidad_ninos']) ? 1 : 0,
                ':compatibilidad_perros' => !empty($datos['compatibilidad_perros']) ? 1 : 0,
                ':compatibilidad_gatos' => !empty($datos['compatibilidad_gatos']) ? 1 : 0,
                ':descripcion' => trim((string) ($datos['descripcion'] ?? '')),
                ':estado_adopcion' => (string) ($datos['estado_adopcion'] ?? 'disponible'),
                ':estado_publicacion' => (string) ($datos['estado_publicacion'] ?? 'borrador'),
                ':id_mascota' => $idMascota,
                ':id_refugio' => $idRefugio,
            ]);

            if (!$resultado) {
                $pdo->rollBack();
                return false;
            }

            self::guardarImagenes($pdo, $idMascota, $imagenes);
            $pdo->commit();
        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }

        self::$cache = null;
        return true;
    }

    private static function guardarImagenes(PDO $pdo, int $idMascota, array $imagenes): void
    {
        if ($imagenes === []) {
            return;
        }

        $consulta = $pdo->prepare(
            'INSERT INTO mascotas_imagenes (id_mascota, ruta_imagen)
             VALUES (:id_mascota, :ruta_imagen)'
        );

        foreach ($imagenes as $imagen) {
            if (!is_string($imagen) || basename($imagen) !== $imagen) {
                throw new InvalidArgumentException('La ruta de la imagen no es válida.');
            }

            $consulta->execute([
                ':id_mascota' => $idMascota,
                ':ruta_imagen' => $imagen,
            ]);
        }
    }

    /** Convierte las columnas de la tabla mascotas al formato de las vistas actuales. */
    private static function normalizarRegistro(array $registro): array
    {
        $edadMeses = isset($registro['edad_meses'])
            ? (int) $registro['edad_meses']
            : null;

        if ($edadMeses === null) {
            $edad = 'No especificada';
        } elseif ($edadMeses < 12) {
            $edad = $edadMeses . ($edadMeses === 1 ? ' mes' : ' meses');
        } else {
            $anios = intdiv($edadMeses, 12);
            $mesesRestantes = $edadMeses % 12;
            $edad = $anios . ($anios === 1 ? ' año' : ' años');

            if ($mesesRestantes > 0) {
                $edad .= ' ' . $mesesRestantes
                    . ($mesesRestantes === 1 ? ' mes' : ' meses');
            }
        }

        return [
            'id' => (int) $registro['id_mascota'],
            'nombre' => (string) $registro['nombre'],
            'especie' => ucfirst((string) $registro['especie']),
            'raza' => (string) ($registro['raza'] ?? ''),
            'edad' => $edad,
            'edad_meses' => $edadMeses,
            'tamano' => ucfirst((string) ($registro['tamanio'] ?? 'No especificado')),
            'sexo' => ucfirst((string) $registro['sexo']),
            'refugio_id' => (int) $registro['id_refugio'],
            'estado' => str_replace('_', ' ', (string) $registro['estado_adopcion']),
            'pub' => (string) $registro['estado_publicacion'],
            'color' => '#FBE4D8',
            'foto' => $registro['foto'] ?? null,
            'fotos' => $registro['fotos'] ?? [],
            'desc' => (string) ($registro['descripcion'] ?? ''),
            'salud' => (string) ($registro['estado_salud_detalle'] ?? 'No especificado'),
            'vacunado' => (bool) $registro['vacunado'],
            'esterilizado' => (bool) $registro['esterilizado'],
            'ninos' => (bool) $registro['compatibilidad_ninos'],
            'perros' => (bool) $registro['compatibilidad_perros'],
            'gatos' => (bool) $registro['compatibilidad_gatos'],
        ];
    }
}
