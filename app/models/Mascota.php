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

            self::$cache = array_map(
                static fn(array $registro): array => self::normalizarRegistro($registro),
                $consulta->fetchAll()
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

    public static function guardar(array $datos, int $idRefugio): ?int
    {
        $nombre = trim((string) ($datos['nombre'] ?? ''));
        $especie = trim((string) ($datos['especie'] ?? ''));
        $raza = trim((string) ($datos['raza'] ?? ''));
        $sexo = trim((string) ($datos['sexo'] ?? ''));
        $descripcion = trim((string) ($datos['descripcion'] ?? ''));

        if ($idRefugio <= 0 || $nombre === '' || $especie === '' || $raza === '' || $sexo === '' || $descripcion === '') {
            return null;
        }

        $consulta = Database::conectar()->prepare(
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
            return null;
        }

        self::$cache = null;
        return (int) Database::conectar()->lastInsertId();
    }

    public static function actualizar(int $idMascota, array $datos, int $idRefugio): bool
    {
        $consulta = Database::conectar()->prepare(
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

        self::$cache = null;
        return $resultado;
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
