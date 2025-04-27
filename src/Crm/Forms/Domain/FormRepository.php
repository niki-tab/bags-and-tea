<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Domain;

use ChannelManager\Shared\Domain\Criteria\Criteria;
use ChannelManager\Core\Bookings\Infrastructure\Eloquent\BookingEloquentModel;

interface FormRepository
{
    /*public function save(FormEloquentModel $form): void;
    public function search(string $id): ?FormEloquentModel;*/

    public function saveFormSubmission(FormSubmissionModel $formSubmission): void;
}
