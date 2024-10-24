<?php 
namespace App\Application\Account\DTOs;

use DocsMaker\Attributes\Component\ComponentProp;
use DocsMaker\Attributes\Component\ComponentSchema;

#[ComponentSchema(name: 'Document', description: 'Document with contains document number and type. Ex: RG, CPF, CNH, etc. Obs: for now, the document type available is only CPF.')]
class DocumentDTO
{
    #[ComponentProp(
        description: 'The document number. Ex: 12345678900',
        required: true    
    )]
    public ?string $number;

    #[ComponentProp(
        enum: ['CPF'], 
        description: 'The document type for validate the document number. For now only CPF is available.',
        required: true
    )]
    public ?string $type;

    public function __construct(
        ?string $number = null,
        ?string $type = null
    ){
        $this->number = $number;
        $this->type = $type;
    }
}