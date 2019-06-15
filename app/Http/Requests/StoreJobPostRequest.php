<?php

namespace App\Http\Requests;

use App\Rules\JobStep;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostRequest extends FormRequest
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
            'job_category_id' => 'required|integer|exists:job_categories,id',
            'event_id' => 'required|integer|exists:events,id',
            'location_id' => 'sometimes|integer|exists:locations,id',
            'event_date' => 'sometimes|nullable|date',
            'budget' => 'sometimes|nullable|regex:/^[0-9]{1,10}(,[0-9]{3})*(\.[0-9]+)*$/',
            'photos.*' => 'sometimes|nullable|mimes:jpg,jpeg,png|max:10000',
        ];
    }

    public function messages()
    {
        return [
            'job_category_id.*' => 'Please select what you need.',
            'event_id.*' => 'Please select a valid event type.',
            'location_id.*' => 'Invalid location.',
            'budget.regex' => 'Invalid format. Sample correct format is ####.##'
        ];
    }
}
