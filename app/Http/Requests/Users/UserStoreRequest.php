<?php
/*
* Nombre de la clase           : UserStoreRequest.php
* Descripción de la clase      : Request encargado de la validación y normalización de datos para el alta de usuarios del sistema, incluyendo reglas de negocio para nombre, correo electrónico, contraseña, rol, género, CURP, RFC y clave presupuestal.
* Fecha de creación            : 28/09/2025
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
use Illuminate\Validation\Validator;

class UserStoreRequest extends FormRequest
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
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:60',
            ],
            'role' => [
                'required',
                'in:union,worker',
            ],
            'gender' => [
                'required',
                'in:H,M,ND,X',
            ],
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
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator)
        {
            $email = (string) $this->input('email', '');

            if (!str_contains($email, '@'))
            {
                return;
            }

            [, $domain] = array_pad(explode('@', $email, 2), 2, '');

            if ($domain === '')
            {
                $validator->errors()->add(
                    'email',
                    'El correo electrónico debe incluir un dominio válido.'
                );
                return;
            }

            if (preg_match('/\d/', $domain))
            {
                $validator->errors()->add(
                    'email',
                    'El dominio del correo no debe contener números.'
                );
            }
        });
    }


    public function messages(): array
    {
        return [
            'name.required' => 'El nombre completo es obligatorio.',
            'name.regex' => 'El nombre no debe contener números.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'role.required' => 'Selecciona un rol.',
            'gender.required' => 'Selecciona una opción de sexo / género.',
            'curp.required' => 'La CURP es obligatoria.',
            'curp.size' => 'La CURP debe tener 18 caracteres.',
            'curp.uppercase' => 'La CURP debe estar en mayúsculas.',
            'curp.regex' => 'La CURP no tiene un formato válido.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.uppercase' => 'El RFC debe estar en mayúsculas.',
            'rfc.regex' => 'El RFC no tiene un formato válido.',
            'budget_key.required' => 'La clave presupuestal es obligatoria.',
            'budget_key.regex' => 'La clave presupuestal solo permite letras, números y guion.',
        ];
    }
}
