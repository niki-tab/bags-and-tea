<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Application\UseCases;

use Ramsey\Uuid\Uuid;
use Src\Crm\Forms\Domain\FormRepository;
use Src\Crm\Forms\Domain\FormSubmissionModel;


final class FormSubmissionCreator
{
    public function __construct(
        private FormRepository $formRepository,
    ) {}
    public function __invoke(
        string $crmFormId,
        array $crmFormAnswers,

    ): void {
        

        $formSubmission = new FormSubmissionModel(
            [
                'crm_form_id' => $crmFormId,
                'crm_form_answers' => $crmFormAnswers,
            ]
        );

        $this->formRepository->saveFormSubmission($formSubmission);

        
    }
}
