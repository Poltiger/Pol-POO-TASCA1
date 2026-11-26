<?php
namespace App\Models;

use App\Exceptions\EmailInvalidException;
use DateTime;
use LogicException;

// Guarda les dades de l'usuari, els préstecs i els avisos temporals.
class Usuari
{
    private int $id;
    private string $nom;
    private string $email;
    private array $materialsPrestats = [];
    private DateTime $dataRegistre;
    private array $notificacions = [];

    public function __construct(int $id, string $nom, string $email)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->__set('email', $email);
        $this->dataRegistre = new DateTime();
    }

    public function __get(string $propietat): mixed
    {
        return match ($propietat) {
            'id', 'nom', 'email', 'dataRegistre' => $this->$propietat,
            default => null,
        };
    }

    public function __set(string $propietat, mixed $valor): void
    {
        if ($propietat !== 'email') {
            throw new LogicException('Només es pot canviar el correu.');
        }
        if (!is_string($valor) || !$this->validarEmail($valor)) {
            throw new EmailInvalidException('El correu no és vàlid.');
        }
        $this->email = $valor;
    }

    private function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Els avisos duren només fins que es mostren o s'acaba la petició.
    public function __sleep(): array
    {
        return ['id', 'nom', 'email', 'materialsPrestats', 'dataRegistre'];
    }

    public function __wakeup(): void
    {
        $this->notificacions = [];
    }

    public function __toString(): string
    {
        return "{$this->nom} <{$this->email}> - {$this->getNumeroMaterialsPrestats()} préstecs";
    }

    public function afegirPrestec(Material $material): void
    {
        $this->materialsPrestats[$material->getId()] = $material;
    }

    public function eliminarPrestec(int $materialId): void
    {
        unset($this->materialsPrestats[$materialId]);
    }

    public function getMaterialsPrestats(): array
    {
        return array_values($this->materialsPrestats);
    }

    public function getNumeroMaterialsPrestats(): int
    {
        return count($this->materialsPrestats);
    }

    public function afegirNotificacio(string $missatge): void
    {
        $this->notificacions[] = $missatge;
    }

    public function obtenirNotificacions(): array
    {
        $avisos = $this->notificacions;
        $this->notificacions = [];
        return $avisos;
    }
}
