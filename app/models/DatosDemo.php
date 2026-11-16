<?php
/** Datos de demostración. Estos registros no se guardan en MySQL. */
class DatosDemo
{
    public static function mascotas(): array
    {
        $mascotas = [
            self::crearMascota([
                'id' => 1,
                'nombre' => 'Luna',
                'especie' => 'Perro',
                'raza' => 'Labrador mestizo',
                'edad' => '2 años',
                'tamano' => 'Mediano',
                'sexo' => 'Hembra',
                'refugio_id' => 1,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#FBE4D8',
                'desc' => 'Juguetona y muy cariñosa. Busca una familia activa.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => true,
                'gatos' => false,
            ]),
            self::crearMascota([
                'id' => 2,
                'nombre' => 'Simón',
                'especie' => 'Gato',
                'raza' => 'Común europeo',
                'edad' => '1 año',
                'tamano' => 'Pequeño',
                'sexo' => 'Macho',
                'refugio_id' => 2,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#F1EAE0',
                'desc' => 'Tranquilo y curioso. Ideal para apartamentos.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => false,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 3,
                'nombre' => 'Rocky',
                'especie' => 'Perro',
                'raza' => 'Criollo',
                'edad' => '4 años',
                'tamano' => 'Grande',
                'sexo' => 'Macho',
                'refugio_id' => 1,
                'estado' => 'en proceso',
                'pub' => 'publicado',
                'color' => '#EAE6E1',
                'desc' => 'Leal y protector. Ya tiene una solicitud en revisión.',
                'salud' => 'Buena',
                'ninos' => true,
                'perros' => true,
                'gatos' => false,
            ]),
            self::crearMascota([
                'id' => 4,
                'nombre' => 'Michi',
                'especie' => 'Gato',
                'raza' => 'Siamés',
                'edad' => '3 años',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 2,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#FBE4D8',
                'desc' => 'Elegante y vocal. Necesita atención diaria.',
                'salud' => 'Excelente',
                'ninos' => false,
                'perros' => false,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 5,
                'nombre' => 'Toby',
                'especie' => 'Perro',
                'raza' => 'Beagle',
                'edad' => '6 meses',
                'tamano' => 'Mediano',
                'sexo' => 'Macho',
                'refugio_id' => 3,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#F1EAE0',
                'desc' => 'Cachorro lleno de energía. Requiere paciencia.',
                'salud' => 'Excelente',
                'esterilizado' => false,
                'ninos' => true,
                'perros' => true,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 6,
                'nombre' => 'Nina',
                'especie' => 'Conejo',
                'raza' => 'Enano',
                'edad' => '1 año',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 3,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#EAE6E1',
                'desc' => 'Dulce y silenciosa. Necesita jaula amplia.',
                'salud' => 'Buena',
                'ninos' => true,
                'perros' => false,
                'gatos' => false,
            ]),
            self::crearMascota([
                'id' => 7,
                'nombre' => 'Max',
                'especie' => 'Perro',
                'raza' => 'Pastor mestizo',
                'edad' => '3 años',
                'tamano' => 'Grande',
                'sexo' => 'Macho',
                'refugio_id' => 1,
                'estado' => 'adoptado',
                'pub' => 'publicado',
                'color' => '#F1EAE0',
                'desc' => 'Ya encontró su hogar.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => true,
                'gatos' => false,
            ]),
            self::crearMascota([
                'id' => 9,
                'nombre' => 'Bella',
                'especie' => 'Perro',
                'raza' => 'Poodle mestizo',
                'edad' => '5 años',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 3,
                'estado' => 'disponible',
                'pub' => 'borrador',
                'color' => '#FBE4D8',
                'desc' => 'Tranquila y hogareña. Pendiente de publicar.',
                'salud' => 'Buena',
                'ninos' => true,
                'perros' => true,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 10,
                'nombre' => 'Nube',
                'especie' => 'Perro',
                'raza' => 'Westie',
                'edad' => '3 años',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 2,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#F1EAE0',
                'desc' => 'Blanca, juguetona y muy sociable. Le encanta saltar y jugar.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => true,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 11,
                'nombre' => 'Pompón',
                'especie' => 'Perro',
                'raza' => 'Pomerania',
                'edad' => '2 años',
                'tamano' => 'Pequeño',
                'sexo' => 'Macho',
                'refugio_id' => 3,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#FBE4D8',
                'desc' => 'Pequeño y valiente, con un pelaje naranja muy esponjoso.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => true,
                'gatos' => false,
            ]),
            self::crearMascota([
                'id' => 12,
                'nombre' => 'Kiko',
                'especie' => 'Perro',
                'raza' => 'Jack Russell',
                'edad' => '1 año',
                'tamano' => 'Pequeño',
                'sexo' => 'Macho',
                'refugio_id' => 2,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#EAE6E1',
                'desc' => 'Enérgico y curioso. Ideal para familias activas.',
                'salud' => 'Buena',
                'ninos' => true,
                'perros' => true,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 13,
                'nombre' => 'Galleta',
                'especie' => 'Perro',
                'raza' => 'Corgi galés',
                'edad' => '5 meses',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 1,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#FBE4D8',
                'desc' => 'Cachorra alegre que aprende rápido. Necesita paciencia y juego.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => true,
                'gatos' => true,
            ]),
            self::crearMascota([
                'id' => 14,
                'nombre' => 'Dulce',
                'especie' => 'Perro',
                'raza' => 'Papillon mestizo',
                'edad' => '4 años',
                'tamano' => 'Pequeño',
                'sexo' => 'Hembra',
                'refugio_id' => 3,
                'estado' => 'disponible',
                'pub' => 'publicado',
                'color' => '#F1EAE0',
                'desc' => 'Tranquila y cariñosa, perfecta para apartamentos.',
                'salud' => 'Excelente',
                'ninos' => true,
                'perros' => false,
                'gatos' => true,
            ]),
        ];

        // Fotografías incluidas en public/assets/img/mascotas.
        $fotos = [
            1 => '9.jpg',
            3 => 'p1.jpg',
            5 => '6.jpg',
            9 => '8.jpg',
            10 => '10.jpg',
            11 => '3.jpg',
            12 => '5.jpg',
            13 => 'perro2.jpg',
            14 => '7.jpg',
        ];

        foreach ($mascotas as &$mascota) {
            if (isset($fotos[$mascota['id']])) {
                $mascota['foto'] = $fotos[$mascota['id']];
            }
        }
        unset($mascota);

        return $mascotas;
    }

    // Datos demo deshabilitados para permitir registro real de usuarios, refugios y administradores.
    // public static function refugios(): array
    // {
    //     return [
    //         [
    //             'id' => 1,
    //             'nombre' => 'Huellas Felices',
    //             'ciudad' => 'Medellín',
    //             'tipo' => 'Fundación',
    //             'anio' => 2015,
    //             'contacto' => 'Camila Restrepo',
    //             'cargo' => 'Coordinadora',
    //             'correo' => 'camila@huellasfelices.org',
    //             'telefono' => '(300) 123 4567',
    //             'estado' => 'activo',
    //         ],
    //         [
    //             'id' => 2,
    //             'nombre' => 'Refugio Patitas',
    //             'ciudad' => 'Bogotá',
    //             'tipo' => 'Albergue',
    //             'anio' => 2018,
    //             'contacto' => 'Jorge Salas',
    //             'cargo' => 'Director',
    //             'correo' => 'jorge@patitas.org',
    //             'telefono' => '(310) 987 6543',
    //             'estado' => 'activo',
    //         ],
    //         [
    //             'id' => 3,
    //             'nombre' => 'Amigos Peludos',
    //             'ciudad' => 'Cali',
    //             'tipo' => 'Rescatista independiente',
    //             'anio' => 2020,
    //             'contacto' => 'Ana Ríos',
    //             'cargo' => 'Fundadora',
    //             'correo' => 'ana@amigospeludos.org',
    //             'telefono' => '(320) 456 7890',
    //             'estado' => 'activo',
    //         ],
    //     ];
    // }

    public static function refugios(): array
    {
        return [];
    }

    // public static function usuarios(): array
    // {
    //     return [
    //         [
    //             'nombre' => 'María Gómez',
    //             'email' => 'maria@ejemplo.com',
    //             'telefono' => '(300) 111 2233',
    //             'estado' => 'activo',
    //             'fecha' => '2026-02-11',
    //         ],
    //         [
    //             'nombre' => 'Carlos Pérez',
    //             'email' => 'carlos@ejemplo.com',
    //             'telefono' => '(310) 222 3344',
    //             'estado' => 'activo',
    //             'fecha' => '2026-03-02',
    //         ],
    //         [
    //             'nombre' => 'Laura Torres',
    //             'email' => 'laura@ejemplo.com',
    //             'telefono' => '(320) 333 4455',
    //             'estado' => 'inactivo',
    //             'fecha' => '2026-04-19',
    //         ],
    //     ];
    // }

    public static function usuarios(): array
    {
        return [];
    }

    // public static function solicitudes(): array
    // {
    //     return [
    //         [
    //             'id' => 101,
    //             'mascota_id' => 1,
    //             'solicitante' => 'María Gómez',
    //             'refugio_id' => 1,
    //             'estado' => 'pendiente',
    //             'fecha' => '2026-08-01',
    //             'vivienda' => 'Casa con patio',
    //             'motivo' => 'Tengo experiencia con perros grandes y quiero darle un hogar a Luna.',
    //         ],
    //         [
    //             'id' => 102,
    //             'mascota_id' => 3,
    //             'solicitante' => 'Carlos Pérez',
    //             'refugio_id' => 1,
    //             'estado' => 'pendiente',
    //             'fecha' => '2026-08-03',
    //             'vivienda' => 'Apartamento sin patio',
    //             'motivo' => 'Busco un compañero leal para mis rutinas diarias.',
    //         ],
    //         [
    //             'id' => 103,
    //             'mascota_id' => 2,
    //             'solicitante' => 'Laura Torres',
    //             'refugio_id' => 2,
    //             'estado' => 'aprobada',
    //             'fecha' => '2026-07-20',
    //             'vivienda' => 'Apartamento sin patio',
    //             'motivo' => 'Simón sería ideal para mi apartamento tranquilo.',
    //         ],
    //         [
    //             'id' => 104,
    //             'mascota_id' => 7,
    //             'solicitante' => 'María Gómez',
    //             'refugio_id' => 1,
    //             'estado' => 'rechazada',
    //             'fecha' => '2026-06-14',
    //             'vivienda' => 'Apartamento sin patio',
    //             'motivo' => 'Me encantaría adoptar a Max.',
    //             'motivo_rechazo' => 'Espacio insuficiente para un perro de talla grande.',
    //         ],
    //     ];
    // }

    public static function solicitudes(): array
    {
        return [];
    }

    // public static function notificaciones(string $rol): array
    // {
    //     $notificaciones = [
    //         'usuario' => [
    //             [
    //                 'Tu solicitud por Luna fue recibida',
    //                 'El refugio Huellas Felices la está revisando.',
    //                 'Hace 2 días',
    //                 false,
    //                 'envelope',
    //             ],
    //             [
    //                 'Solicitud por Max rechazada',
    //                 'Espacio insuficiente para un perro de talla grande.',
    //                 'Hace 3 semanas',
    //                 true,
    //                 'x-circle',
    //             ],
    //         ],
    //         'refugio' => [
    //             [
    //                 'Nueva solicitud recibida',
    //                 'María Gómez solicitó adoptar a Luna.',
    //                 'Hace 2 días',
    //                 false,
    //                 'file-earmark-text',
    //             ],
    //             [
    //                 'Nueva solicitud recibida',
    //                 'Carlos Pérez solicitó adoptar a Rocky.',
    //                 'Hace 1 día',
    //                 false,
    //                 'file-earmark-text',
    //             ],
    //         ],
    //         'admin' => [
    //             [
    //                 'Nuevo refugio registrado',
    //                 'Amigos Peludos completó su registro.',
    //                 'Hace 1 semana',
    //                 true,
    //                 'building',
    //             ],
    //         ],
    //     ];
    //
    //     return $notificaciones[$rol] ?? [];
    // }

    public static function notificaciones(string $rol): array
    {
        return [];
    }

    public static function porId(array $lista, int $id): ?array
    {
        foreach ($lista as $elemento) {
            if ((int) ($elemento['id'] ?? 0) === $id) {
                return $elemento;
            }
        }

        return null;
    }

    /** Completa los valores por defecto para no repetirlos en cada registro demo. */
    private static function crearMascota(array $datos): array
    {
        $valoresPorDefecto = [
            'vacunado' => true,
            'esterilizado' => true,
            'ninos' => false,
            'perros' => false,
            'gatos' => false,
        ];

        return array_merge($valoresPorDefecto, $datos);
    }
}
