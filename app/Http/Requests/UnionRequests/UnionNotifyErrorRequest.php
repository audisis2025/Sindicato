<?php
/*
* ===========================================================
* Nombre de la clase       : UnionNotifyErrorRequest
* Descripción de la clase  : Validación para notificar errores al trabajador
*                           en un paso de la solicitud.
* Fecha de creación       : 13/01/2026
* Elaboró                 : Iker Piza
* Fecha de liberación     : 13/01/2026
* Autorizó                : Líder Técnico
* Versión                 : 1.0
* Revisor                 : QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Requests\UnionRequests;

use Illuminate\Foundation\Http\FormRequest;

class UnionNotifyErrorRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'error_message' => $this->error_message ? trim($this->error_message) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'error_message' => ['required', 'string', 'max:500'],
		];
	}
}
