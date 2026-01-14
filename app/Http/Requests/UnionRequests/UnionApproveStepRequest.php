<?php
/*
* ===========================================================
* Nombre de la clase       : UnionApproveStepRequest
* Descripción de la clase  : Validación para aprobar/rechazar pasos
*                           de una solicitud de trámite.
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

class UnionApproveStepRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'comments' => $this->comments ? trim($this->comments) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'result' => ['required', 'in:approve,reject'],
			'comments' => ['nullable', 'string', 'max:1000'],
		];
	}
}
