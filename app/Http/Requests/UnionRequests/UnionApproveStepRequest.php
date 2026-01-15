<?php
/*
* Nombre de la clase           : UnionApproveStepRequest.php
* Descripción de la clase      : Request encargado de la validación de la aprobación o rechazo de un paso dentro de una solicitud de trámite por parte del sindicato.
* Fecha de creación            : 21/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 19/12/2025
* Autorizó                     :
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
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
