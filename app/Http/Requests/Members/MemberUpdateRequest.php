<?php
/*
* ===========================================================
* Nombre de la clase       : MemberUpdateRequest
* Descripción de la clase  : Validación para la actualización de
*                           trabajadores (rol worker) desde el Sindicato.
* Fecha de creación       : 13/01/2026
* Elaboró                 : Iker Piza
* Fecha de liberación     : 13/01/2026
* Autorizó                : Líder Técnico
* Versión                 : 1.0
* Revisor                 : QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class MemberUpdateRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'name' => $this->name ? trim($this->name) : null,
			'email' => $this->email ? trim($this->email) : null,
			'curp' => $this->curp ? mb_strtoupper(trim($this->curp)) : null,
			'rfc'  => $this->rfc ? mb_strtoupper(trim($this->rfc)) : null,
			'budget_key' => $this->budget_key ? trim($this->budget_key) : null,
		]);
	}

	public function rules(): array
	{
		$workerId = $this->route('id') ?? $this->route('member') ?? $this->route('worker') ?? null;

		return [
			'name' => ['required','string','max:120','regex:/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s\'.-]+$/u'],
			'email' => [
				'required','string','email:rfc,dns','max:120',
				Rule::unique('users','email')->ignore($workerId),
			],
			'gender' => ['required','in:H,M,ND,X'],
			'curp' => ['required','string','size:18','uppercase','regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/'],
			'rfc'  => ['required','string','uppercase','regex:/^([A-ZÑ&]{3,4})\d{6}([A-Z0-9]{3})$/'],
			'budget_key' => ['required','string','max:30','regex:/^[A-Za-z0-9-]+$/'],
			'active' => ['required','boolean'],
		];
	}

	public function withValidator(Validator $validator): void
	{
		$validator->after(function (Validator $validator)
		{
			$email = (string) $this->input('email', '');

			if (str_contains($email, '@'))
			{
				[, $domain] = array_pad(explode('@', $email, 2), 2, '');

				if ($domain === '')
				{
					$validator->errors()->add('email', 'El correo electrónico debe incluir un dominio válido.');
					return;
				}

				if (preg_match('/\d/', $domain))
				{
					$validator->errors()->add('email', 'El dominio del correo no debe contener números.');
				}
			}
		});
	}
}
