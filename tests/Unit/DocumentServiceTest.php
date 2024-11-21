<?php

namespace Tests\Unit\Services;

use App\Services\DocumentService;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{

    public function testMaximumContentLength()
    {
        $data = [
            'documentos' => [
                [
                    'categoria' => 'Remessa',
                    'titulo' => 'Teste',
                    'conteúdo' => str_repeat('a', 10000),
                ],
                [
                    'categoria' => 'Remessa',
                    'titulo' => 'Teste',
                    'conteúdo' => str_repeat('a', 100001),
                ],
            ],
        ];

        $service = new DocumentService();
        $validator = $service->validateData($data);

        $this->assertFalse($validator->passes());
        $this->assertArrayNotHasKey('documentos.0.conteúdo', $validator->errors()->toArray());
        $this->assertArrayHasKey('documentos.1.conteúdo', $validator->errors()->toArray());
    }

    public function testCategoryTitleValidation()
    {
        $service = new DocumentService();

        $data = [
            'documentos' => [
                [
                    'categoria' => 'Remessa',
                    'titulo' => 'Teste semestre',
                    'conteúdo' => 'Conteúdo',
                ],
                [
                    'categoria' => 'Remessa Parcial',
                    'titulo' => 'Teste Janeiro',
                    'conteúdo' => 'Conteúdo',
                ],
            ],
        ];
        $validator = $service->validateData($data);

        $this->assertTrue($validator->passes());

        $data = [
            'documentos' => [
                [
                    'categoria' => 'Remessa',
                    'titulo' => 'Teste',
                    'conteúdo' => 'Conteúdo',
                ],
                [
                    'categoria' => 'Remessa Parcial',
                    'titulo' => 'Teste',
                    'conteúdo' => 'Conteúdo',
                ],
            ],
        ];

        $service = new DocumentService();
        $validator = $service->validateData($data);

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('documentos.0.titulo', $validator->errors()->toArray());
        $this->assertArrayHasKey('documentos.1.titulo', $validator->errors()->toArray());
    }
}