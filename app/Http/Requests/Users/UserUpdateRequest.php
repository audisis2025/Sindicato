<?php
/*
* Nombre de la clase           : UserUpdateRequest.php
* Descripción de la clase      : Request encargado de la validación y normalización de datos para la actualización de usuarios del sistema, incluyendo reglas de negocio para nombre, correo electrónico, rol, género, CURP, RFC, clave presupuestal, estatus y contraseña.
* Fecha de creación            : 27/09/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      : 
*/


namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'        => $this->name ? trim($this->name) : null,
            'email'       => $this->email ? trim($this->email) : null,
            'curp'        => $this->curp ? mb_strtoupper(trim($this->curp)) : null,
            'rfc'         => $this->rfc ? mb_strtoupper(trim($this->rfc)) : null,
            'budget_key'  => $this->budget_key ? trim($this->budget_key) : null,
        ]);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:120',
                'regex:/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s\'.-]+$/u',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:120',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role' => ['required', 'in:union,worker'],
            'gender' => ['required', 'in:H,M,ND,X'],
            'curp' => [
                'required',
                'string',
                'size:18',
                'uppercase',
                'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/',
            ],
            'rfc' => [
                'required',
                'string',
                'uppercase',
                'regex:/^([A-ZÑ&]{3,4})\d{6}([A-Z0-9]{3})$/',
            ],
            'budget_key' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9-]+$/',
            ],
            'active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'max:60'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator)
        {
            if ($this->role === 'worker' && str_contains((string)$this->email, '@'))
            {
                $domain = explode('@', $this->email)[1] ?? '';

                if (preg_match('/\d/', $domain))
                {
                    $validator->errors()->add(
                        'email',
                        'El dominio del correo no debe contener números.'
                    );
                }
            }
        });
    }
}
