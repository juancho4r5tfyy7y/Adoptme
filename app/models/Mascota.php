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

        if (!USAR_BD) {
            self::$cache = DatosDemo::mascotas();
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
            // Se conserva la vista de demostración, pero el fallo queda registrado.
            error_log('ADOPT ME - No fue posible consultar las mascotas: ' . $error->getMessage());
            self::$cache = DatosDemo::mascotas();
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
