<?php
namespace App\Data;

use App\Models\Biblioteca;
use App\Models\DVD;
use App\Models\Llibre;
use App\Models\Revista;
use App\Models\Usuari;
use DateTime;

// Prepara les dades inicials per poder provar la biblioteca.
class DataGenerator
{
    public static function crear(): Biblioteca
    {
        $biblioteca = new Biblioteca('Biblioteca digital');
        $biblioteca->afegirMaterial(new Llibre(1, 'El nom de la rosa', 'Umberto Eco', 1980, 512));
        $biblioteca->afegirMaterial(new Llibre(2, 'El petit príncep', 'Antoine de Saint-Exupéry', 1943, 96));
        $biblioteca->afegirMaterial(new Llibre(3, '1984', 'George Orwell', 1949, 328));
        $biblioteca->afegirMaterial(new Llibre(4, 'La plaça del Diamant', 'Mercè Rodoreda', 1962, 192));
        $biblioteca->afegirMaterial(new Revista(5, 'National Geographic', 'National Geographic Society', 1888, 1));
        $biblioteca->afegirMaterial(new Revista(6, 'Wired', 'Louis Rossetto i Jane Metcalfe', 1993, 1));
        $biblioteca->afegirMaterial(new Revista(7, 'Time', 'Briton Hadden i Henry Luce', 1923, 1));
        $biblioteca->afegirMaterial(new DVD(8, 'Cinema Paradiso', 'Giuseppe Tornatore', 1988, 124));
        $biblioteca->afegirMaterial(new DVD(9, 'Jurassic Park', 'Steven Spielberg', 1993, 127));
        $biblioteca->afegirMaterial(new DVD(10, 'El viatge de Chihiro', 'Hayao Miyazaki', 2001, 125));

        $biblioteca->afegirUsuari(new Usuari(1, 'Anna Puig', 'anna@example.com'));
        $biblioteca->afegirUsuari(new Usuari(2, 'Marc Soler', 'marc@example.com'));
        $biblioteca->afegirUsuari(new Usuari(3, 'Laia Serra', 'laia@example.com'));
        $biblioteca->afegirUsuari(new Usuari(4, 'Pau Roca', 'pau@example.com'));

        $biblioteca->prestarMaterial(3, 2, new DateTime('-21 days'));
        $biblioteca->prestarMaterial(5, 3);
        $biblioteca->reservarMaterial(9, 4);
        return $biblioteca;
    }
}
