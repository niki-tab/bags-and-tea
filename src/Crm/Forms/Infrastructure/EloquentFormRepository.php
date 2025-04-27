<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Infrastructure;

use Src\Crm\Forms\Domain\FormRepository;
use Src\Crm\Forms\Domain\FormSubmissionModel;
use Src\Crm\Forms\Infrastructure\Eloquent\FormEloquentModel;

final class EloquentFormRepository implements FormRepository
{
    public function saveFormSubmission(FormSubmissionModel $formSubmission): void
    {
        $formSubmission->save();
    }
}
