<?php
/*
* Nombre de la clase           : UnionNotifyErrorRequest.php
* Descripción de la clase      : Request encargado de la validación y normalización del mensaje de error enviado por el sindicato al trabajador cuando se requiere corrección en un paso del trámite.
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
