<?php
namespace App\Models;

use App\Interfaces\Reservable;
use InvalidArgumentException;

// Representa un DVD i la seva durada en minuts.
class DVD extends Material implements Reservable
{
    private int $duracio;
    private ?string $usuariReserva = null;

    public function __construct(int $id, string $titol, string $autor, int $anyPublicacio, int $duracio)
    {
        parent::__construct($id, $titol, $autor, $anyPublicacio);
        $this->setDuracio($duracio);
    }

    public function calcularMulta(int $diesRetard): float
    {
        return $diesRetard * 1.00;
    }

    public function getTipus(): string
    {
        return 'DVD';
    }

    public function getDuracio(): int
    {
        return $this->duracio;
    }

    public function setDuracio(int $minuts): void
    {
        if ($minuts <= 0) {
            throw new InvalidArgumentException("La durada ha de ser positiva.");
        }
        $this->duracio = $minuts;
    }

    public function getDuracioFormatada(): string
    {
        $hores = intdiv($this->duracio, 60);
        $minuts = $this->duracio % 60;
        return "{$hores}h {$minuts}min";
    }

    public function reservar(string $nomUsuari): bool
    {
        if ($this->estaReservat()) {
            return false;
        }
        $this->usuariReserva = $nomUsuari;
        $this->registrarAccio('reservat', $nomUsuari);
        return true;
    }

    public function cancelarReserva(): bool
    {
        if (!$this->estaReservat()) {
            return false;
        }
        $this->registrarAccio('reserva_cancel·lada', $this->usuariReserva);
        $this->usuariReserva = null;
        return true;
    }

    public function estaReservat(): bool
    {
        return $this->usuariReserva !== null;
    }

    public function getUsuariReserva(): ?string
    {
        return $this->usuariReserva;
    }
}
