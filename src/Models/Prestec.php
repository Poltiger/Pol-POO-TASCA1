<?php
namespace App\Models;

use DateTime;

// Relaciona un material amb un usuari i calcula el retard.
class Prestec
{
    private DateTime $dataPrestec;
    private ?DateTime $dataRetorn = null;

    public function __construct(
        private Material $material,
        private Usuari $usuari,
        private int $diesLimitPrestec = 14,
        ?DateTime $dataPrestec = null
    ) {
        $this->dataPrestec = $dataPrestec ?? new DateTime();
    }

    public function retornar(): void
    {
        $this->dataRetorn = new DateTime();
    }

    public function estaActiu(): bool
    {
        return $this->dataRetorn === null;
    }

    public function calcularDiesRetard(): int
    {
        return max(0, -$this->getDiesPendents());
    }

    public function calcularMulta(): float
    {
        return $this->material->calcularMulta($this->calcularDiesRetard());
    }

    public function estaVencut(): bool
    {
        return $this->getDiesPendents() < 0;
    }

    // Es comparen dies de calendari sense tenir en compte l'hora.
    public function getDiesPendents(): int
    {
        $inici = clone $this->dataPrestec;
        $final = $this->dataRetorn === null ? new DateTime() : clone $this->dataRetorn;
        $diesPassats = (int) $inici->setTime(0, 0)->diff($final->setTime(0, 0))->format('%r%a');
        return $this->diesLimitPrestec - $diesPassats;
    }

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function getUsuari(): Usuari
    {
        return $this->usuari;
    }

    public function getDataPrestec(): DateTime
    {
        return $this->dataPrestec;
    }

    public function getDataRetorn(): ?DateTime
    {
        return $this->dataRetorn;
    }

    public function getDiesLimitPrestec(): int
    {
        return $this->diesLimitPrestec;
    }
}
