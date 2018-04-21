<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'max_tenants' => 'max:2147483647|nullable|numeric',
            'tenants.*' => 'exists:users,id',
            'view_count' => 'max:2147483647|nullable|numeric',
        ];
    }
}
