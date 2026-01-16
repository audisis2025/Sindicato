<?php
/*
* Nombre de la clase           : ProcedureStoreRequest.php
* Descripción de la clase      : Request encargado de la validación y normalización de datos para los tramites
* Fecha de creación            : 26/09/2025
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
namespace App\Http\Requests\Procedures;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProcedureStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'opening_date' => ['required', 'date'],
            'closing_date' => ['required', 'date', 'after_or_equal:opening_date'],
            'estimated_days' => ['required', 'integer', 'min:1', 'max:365'],

            'steps' => ['required', 'array', 'min:1'],
            'steps.*.order' => ['required', 'integer', 'min:1'],
            'steps.*.step_name' => ['required', 'string', 'max:255'],
            'steps.*.step_description' => ['nullable', 'string', 'max:2000'],
            'steps.*.next_step_if_fail' => ['nullable', 'integer', 'min:1'],
            'steps.*.requires_file' => ['required', 'in:yes,no'],
            'steps.*.file_path' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $steps = $this->input('steps', []);

            if (!is_array($steps) || count($steps) < 1) {
                return;
            }

            $total = count($steps);

            foreach ($steps as $i => $step) {
                $order = (int) ($step['order'] ?? 0);
                $next  = (int) ($step['next_step_if_fail'] ?? 0);

                if (!empty($step['next_step_if_fail'])) {
                    if ($next < 1 || $next > $total) {
                        $validator->errors()->add("steps.$i.next_step_if_fail", 'El flujo alterno apunta a un paso inexistente.');
                    }
                    if ($next === $order) {
                        $validator->errors()->add("steps.$i.next_step_if_fail", 'Un paso no puede tener flujo alterno a sí mismo.');
                    }
                }
            }
        });
    }
}
