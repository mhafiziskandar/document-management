<?php

namespace App\Rules;

use App\Models\FolderType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class FileValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $jenis = request()->input('jenis'); // Assuming 'jenis' is the name of the input field

        if ($jenis == FolderType::FILE) {
            $fail('The file field must be a file.'); // Skip validation if jenis is not FolderType::FILE
        }

        $validator = Validator::make([$attribute => $value], [
            $attribute => 'file|max:12288',
        ]);
    }
}
