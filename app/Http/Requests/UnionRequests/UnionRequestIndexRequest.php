<?php
/*
* Nombre de la clase           : UnionRequestIndexRequest.php
* Descripción de la clase      : Request encargado de la validación y normalización de filtros para el listado de solicitudes de trámites del sindicato, incluyendo palabra clave y estatus.
* Fecha de creación            : 30/09/2025
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


namespace App\Http\Requests\UnionRequests;

use Illuminate\Foundation\Http\FormRequest;

class UnionRequestIndexRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'keyword' => $this->keyword ? trim($this->keyword) : null,
			'status'  => $this->status ? trim($this->status) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'keyword' => ['nullable', 'string', 'max:120'],
			'status' => ['nullable', 'in:started,pending_worker,pending_union,in_progress,completed,cancelled,rejected,pending'],
		];
	}
}
