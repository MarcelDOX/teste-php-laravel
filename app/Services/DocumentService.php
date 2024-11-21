<?php

namespace App\Services;

use App\Jobs\ProcessDocument;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DocumentService
{
    public function convertFileToArray(UploadedFile $file): array
    {
        $fileContent = File::get($file);
        return json_decode($fileContent, true);
    }

    public function validateData(array $data)
    {
        $rules = $this->getValidationRules();
        $validator = Validator::make($data, $rules);

        $this->applyCustomValidation($validator, $data['documentos']);

        return $validator;
    }

    public function addDocumentsToProcess(array $documents): int
    {
        $numberOfDocuments = 0;
        
        foreach ($documents as $documentData) {
            $document = $this->createDocument($documentData);
            ProcessDocument::dispatch($document);
            $numberOfDocuments++;
        }

        return $numberOfDocuments;
    }

    private function createDocument(array $data): array
    {
        $category = Category::firstOrCreate(['name' => $data['categoria']]);
        
        return [
            'title' => $data['titulo'],
            'contents' => $data['conteúdo'],
            'category_id' => $category->id,
        ];
    }

    private function getValidationRules(): array
    {
        return [
            'documentos' => 'required|array',
            'documentos.*.categoria' => 'required|string',
            'documentos.*.titulo' => 'required|string',
            'documentos.*.conteúdo' => 'required|string|max:10000',
        ];
    }

    private function applyCustomValidation($validator, array $documents)
    {
        $validator->after(function ($validator) use ($documents) {
            foreach ($documents as $index => $document) {
                $this->validateDocumentTitle($validator, $index, $document);
            }
        });
    }

    private function validateDocumentTitle($validator, int $index, array $document)
    {
        $title = $document['titulo'];
        $category = $document['categoria'];

        if ($category === 'Remessa' && !$this->titleContainsSemester($title)) {
            $validator->errors()->add(
                "documentos.$index.titulo",
                'O título do documento da categoria "Remessa" deve conter a palavra "semestre".'
            );
        }

        if ($category === 'Remessa Parcial' && !$this->titleContainsMonthName($title)) {
            $validator->errors()->add(
                "documentos.$index.titulo",
                'O título do documento da categoria "Remessa Parcial" deve conter o nome de um mês.'
            );
        }
    }

    private function titleContainsSemester(string $title): bool
    {
        return strpos($title, 'semestre') !== false;
    }

    private function titleContainsMonthName(string $title): bool
    {
        $months = $this->getMonthNames();
        foreach ($months as $month) {
            if (stripos($title, $month) !== false) {
                return true;
            }
        }
        return false;
    }

    private function getMonthNames(): array
    {
        return [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];
    }
}
