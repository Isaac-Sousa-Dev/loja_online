<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->record($model, 'created', [], $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->record($model, 'updated', $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted', $model->getAttributes(), []);
    }

    private function record(Model $model, string $operation, array $before, array $after): void
    {
        AuditLog::create([
            'request_id' => app('request_id'),
            'user_id'    => Auth::user()?->id,
            'store_id'    => Auth::user()?->partner?->store?->id,
            'table'      => $model->getTable(),
            'entity_id'  => $model->getKey(),
            'operation'  => $operation,
            'before'      => $before,
            'after'     => $after,
        ]);
    }
}
