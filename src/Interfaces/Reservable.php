<?php
namespace App\Interfaces;

// Indica quines operacions ha de tenir un material que es pot reservar.
interface Reservable
{
    public function reservar(string $nomUsuari): bool;
    public function cancelarReserva(): bool;
    public function estaReservat(): bool;
    public function getUsuariReserva(): ?string;
}
