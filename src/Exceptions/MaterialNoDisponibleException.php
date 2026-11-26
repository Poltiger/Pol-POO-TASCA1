<?php
namespace App\Exceptions;

use Exception;

// Identifica el material que no es pot prestar o reservar.
class MaterialNoDisponibleException extends Exception
{
    private int $materialId;

    public function __construct(int $materialId, string $missatge = 'Material no disponible')
    {
        $this->materialId = $materialId;
        parent::__construct($missatge);
    }

    public function getMaterialId(): int
    {
        return $this->materialId;
    }

    public function __toString(): string
    {
        return "Error: material amb ID {$this->materialId}. {$this->getMessage()}";
    }
}
