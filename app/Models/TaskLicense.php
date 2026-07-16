<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskLicense extends Model
{
    protected $fillable = [
        'license_key',
        'email',
        'stripe_session_id',
        'device_fingerprints',
        'is_active',
        'max_devices',
        'first_accessed_at',
    ];

    protected $casts = [
        'device_fingerprints' => 'array',
        'is_active'           => 'boolean',
        'first_accessed_at'   => 'datetime',
    ];

    /**
     * Genera una clave única con formato TASK-XXXX-XXXX-XXXX
     */
    public static function generateKey(): string
    {
        do {
            $segments = [
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
            ];
            $key = 'TASK-' . implode('-', $segments);
        } while (self::where('license_key', $key)->exists());

        return $key;
    }

    /**
     * Verifica si la huella del dispositivo ya está registrada en esta licencia.
     */
    public function hasDevice(string $fingerprint): bool
    {
        $fingerprints = $this->device_fingerprints ?? [];
        return in_array($fingerprint, $fingerprints);
    }

    /**
     * Verifica si se puede agregar otro dispositivo.
     */
    public function canAddDevice(): bool
    {
        $fingerprints = $this->device_fingerprints ?? [];
        return count($fingerprints) < $this->max_devices;
    }

    /**
     * Agrega la huella del dispositivo a la licencia.
     */
    public function addDevice(string $fingerprint): bool
    {
        if ($this->hasDevice($fingerprint)) {
            return true; // Ya estaba registrado
        }

        if (!$this->canAddDevice()) {
            return false; // Demasiados dispositivos
        }

        $fingerprints   = $this->device_fingerprints ?? [];
        $fingerprints[] = $fingerprint;

        $this->device_fingerprints = $fingerprints;

        if (!$this->first_accessed_at) {
            $this->first_accessed_at = now();
        }

        $this->save();
        return true;
    }

    /**
     * Resetea los dispositivos registrados (para cuando el cliente cambia de celular).
     */
    public function resetDevices(): void
    {
        $this->device_fingerprints = [];
        $this->save();
    }
}
