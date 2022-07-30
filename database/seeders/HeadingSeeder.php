<?php

namespace Database\Seeders;

use App\Models\Heading;
use Illuminate\Database\Seeder;

class HeadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $headings = [
            "Alimentos y Bebidas",
            "Artesanías y Artes Manuales",
            "Audiovisuales",
            "Bebés y Niños",
            "Belleza y Relajación",
            "Computación y equipo de Oficina",
            "Construcción y Agro",
            "Deportes y Defensa Personal",
            "Educación y Cultura",
            "ElectroHogar",
            "Estado e Instituciones",
            "Eventos y Animación",
            "Hogar y Jardín",
            "Instrumentos y Equipos Musicales",
            "Juegos y Entretenimiento",
            "Libros y Revistas",
            "Mascotas y Accesorios",
            "Pasatiempos y coleccionables",
            "Publicidad y Marketing",
            "Restaurantes y Diversión",
            "Ropa, Calzado y Accesorios",
            "Salud y Bienestar",
            "Seguridad Industrial",
            "Servicios Administrativos y Contables",
            "Servicios en General",
            "Servicios Financieros",
            "Transporte y Aduanas",
            "Vehículos y Maquinaria",
            "Viajes y Turismo",
        ];

        foreach ($headings as $heading) {
            Heading::create([
                'description' => $heading
            ]);
        }
    }
}
