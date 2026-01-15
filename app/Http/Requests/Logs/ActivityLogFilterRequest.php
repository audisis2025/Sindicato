<?php
/*
* ===========================================================
* Nombre de la clase       : ActivityLogFilterRequest.php
* Descripción de la clase  : Validación de filtros para la Bitácora
*                           del sistema (ActivityLog).
* Fecha de creación       : 13/01/2026
* Elaboró                 : Iker Piza
* Fecha de liberación     : 13/01/2026
* Autorizó                : Líder Técnico
* Versión                 : 1.0
* Fecha de mantenimiento  :
* Folio de mantenimiento  :
* Tipo de mantenimiento   :
* Descripción del mantenimiento :
* Responsable             :
* Revisor                 : QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Requests\Logs;

use Illuminate\Foundation\Http\FormRequest;

class ActivityLogFilterRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'keyword' => $this->keyword ? trim($this->keyword) : null,
		]);
	}

	public function rules(): array
	{
		return [
			'date_from' => ['nullable', 'date'],
			'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
			'keyword'   => ['nullable', 'string', 'max:120'],
		];
	}

	public function messages(): array
	{
		return [
			'date_from.date' => 'La fecha inicio no tiene un formato válido.',
			'date_to.date' => 'La fecha fin no tiene un formato válido.',
			'date_to.after_or_equal' => 'La fecha fin debe ser mayor o igual a la fecha inicio.',
			'keyword.max' => 'La palabra clave no debe exceder 120 caracteres.',
		];
	}
}
