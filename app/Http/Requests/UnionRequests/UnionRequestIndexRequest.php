<?php
/*
* ===========================================================
* Nombre de la clase       : UnionRequestIndexRequest
* Descripción de la clase  : Validación de filtros para el listado
*                           de solicitudes sindicales.
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
