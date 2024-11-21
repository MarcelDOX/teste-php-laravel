<?php

namespace App\Http\Controllers;

use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DocumentController extends Controller
{
    private DocumentService $documentService;

    public function __construct(DocumentService $documentService,) 
    {
        $this->documentService = $documentService;
    }

    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json',
        ]);
        
        $file = $request->file('json_file');
        $fileData = $this->documentService->convertFileToArray($file);

        $validation = $this->documentService->validateData($fileData);
        if ($validation->fails()) {
            return view('documents.import')->with('errors', $validation->messages());

        }

        $numberOfDocuments = $this->documentService->addDocumentsToProcess($fileData['documentos']);

        return view('documents.import', [
            'message' => 'Documentos adicionados a fila: ' . $numberOfDocuments,
            'hasDocumentToProcess' => true,
        ]);
    }

    public function dispatch()
    {
        Artisan::call('queue:work --stop-when-empty', []);
        return view('documents.import', ['message' => 'Documentos da fila processados']);
    }
}
