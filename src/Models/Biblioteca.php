<?php
namespace App\Models;

use App\Exceptions\MaterialNoDisponibleException;
use App\Exceptions\MaterialNoPrestatException;
use App\Exceptions\MaterialNoTrobatException;
use App\Exceptions\UsuariNoTrobatException;
use App\Interfaces\Reservable;
use BadMethodCallException;
use DateTime;

// Gestiona el catàleg, els usuaris, les reserves i els préstecs.
class Biblioteca
{
    private array $materials = [];
    private array $usuaris = [];
    private array $prestecs = [];

    public function __construct(private string $nom) {}

    public function afegirMaterial(Material $material): void
    {
        $this->materials[$material->getId()] = $material;
    }

    public function eliminarMaterial(int $id): bool
    {
        if (!isset($this->materials[$id])) {
            return false;
        }
        unset($this->materials[$id]);
        return true;
    }

    public function cercarPerId(int $id): ?Material
    {
        return $this->materials[$id] ?? null;
    }

    public function cercarPerTitol(string $titol): array
    {
        return array_filter($this->materials, function (Material $material) use ($titol) {
            return preg_match('/' . preg_quote($titol, '/') . '/iu', $material->getTitol()) === 1;
        });
    }

    public function cercarPerAutor(string $autor): array
    {
        return array_filter($this->materials, function (Material $material) use ($autor) {
            return preg_match('/' . preg_quote($autor, '/') . '/iu', $material->getAutor()) === 1;
        });
    }

    public function llistarDisponibles(): array
    {
        return array_filter($this->materials, function (Material $material) {
            return $material->isDisponible();
        });
    }

    public function llistarPrestats(): array
    {
        return array_filter($this->materials, function (Material $material) {
            return !$material->isDisponible();
        });
    }

    public function llistarPerTipus(string $tipus): array
    {
        return array_filter($this->materials, function (Material $material) use ($tipus) {
            return $material->getTipus() === $tipus;
        });
    }

    public function llistarPrestecsActius(): array
    {
        return array_filter($this->prestecs, function (Prestec $prestec) {
            return $prestec->estaActiu();
        });
    }

    public function llistarReserves(): array
    {
        return array_filter($this->materials, function (Material $material) {
            return $material instanceof Reservable && $material->estaReservat();
        });
    }

    public function llistarPrestecsUsuari(int $usuariId): array
    {
        return array_filter($this->prestecs, function (Prestec $prestec) use ($usuariId) {
            return $prestec->getUsuari()->id === $usuariId;
        });
    }

    public function afegirUsuari(Usuari $usuari): void
    {
        $this->usuaris[$usuari->id] = $usuari;
    }

    public function cercarUsuari(int|string $identificador): ?Usuari
    {
        if (is_int($identificador)) {
            return $this->usuaris[$identificador] ?? null;
        }
        foreach ($this->usuaris as $usuari) {
            if ($usuari->nom === $identificador) {
                return $usuari;
            }
        }
        return null;
    }

    public function prestarMaterial(int $materialId, int $usuariId, ?DateTime $dataPrestec = null): Prestec
    {
        $material = $this->cercarPerId($materialId);
        $usuari = $this->cercarUsuari($usuariId);
        if ($material === null) {
            throw new MaterialNoTrobatException("No existeix cap material amb ID $materialId.");
        }
        if ($usuari === null) {
            throw new UsuariNoTrobatException("No existeix cap usuari amb ID $usuariId.");
        }
        if (!$material->isDisponible()) {
            throw new MaterialNoDisponibleException($materialId, 'El material ja està prestat.');
        }
        if ($material instanceof Reservable && $material->estaReservat()) {
            if ($material->getUsuariReserva() !== $usuari->nom) {
                throw new MaterialNoDisponibleException($materialId, 'El material està reservat per un altre usuari.');
            }
            $material->cancelarReserva();
        }

        $material->prestar();
        $prestec = new Prestec($material, $usuari, 14, $dataPrestec);
        $this->prestecs[] = $prestec;
        $usuari->afegirPrestec($material);
        $usuari->afegirNotificacio('Préstec realitzat.');
        return $prestec;
    }

    public function retornarMaterial(int $materialId): float
    {
        foreach ($this->llistarPrestecsActius() as $prestec) {
            $material = $prestec->getMaterial();
            if ($material->getId() === $materialId) {
                $prestec->retornar();
                $material->retornar();
                $prestec->getUsuari()->eliminarPrestec($materialId);
                return $prestec->calcularMulta();
            }
        }
        throw new MaterialNoPrestatException("El material amb ID $materialId no està prestat.");
    }

    public function reservarMaterial(int $materialId, int $usuariId): bool
    {
        $material = $this->cercarPerId($materialId);
        $usuari = $this->cercarUsuari($usuariId);
        if ($material === null) {
            throw new MaterialNoTrobatException("No existeix cap material amb ID $materialId.");
        }
        if ($usuari === null) {
            throw new UsuariNoTrobatException("No existeix cap usuari amb ID $usuariId.");
        }
        if (!$material instanceof Reservable) {
            throw new MaterialNoDisponibleException($materialId, 'Les revistes no es poden reservar');
        }
        return $material->reservar($usuari->nom);
    }

    // Resol les crides get... que no tenen un mètode propi.
    public function __call(string $metode, array $arguments): mixed
    {
        return match ($metode) {
            'getLlibres' => $this->llistarPerTipus('Llibre'),
            'getRevistes' => $this->llistarPerTipus('Revista'),
            'getDVDs' => $this->llistarPerTipus('DVD'),
            'getMaterials' => $this->materials,
            'getUsuaris' => $this->usuaris,
            'getNom' => $this->nom,
            default => throw new BadMethodCallException("No existeix el mètode $metode."),
        };
    }

    public function obtenirEstadistiques(): array
    {
        $multes = 0.0;
        foreach ($this->llistarPrestecsActius() as $prestec) {
            $multes += $prestec->calcularMulta();
        }
        $perTipus = [];
        foreach (['Llibre', 'Revista', 'DVD'] as $tipus) {
            $perTipus[$tipus] = count($this->llistarPerTipus($tipus));
        }
        return [
            'total' => count($this->materials),
            'disponibles' => count($this->llistarDisponibles()),
            'prestats' => count($this->llistarPrestats()),
            'reservats' => count($this->llistarReserves()),
            'perTipus' => $perTipus,
            'multesPendents' => $multes,
        ];
    }
}
