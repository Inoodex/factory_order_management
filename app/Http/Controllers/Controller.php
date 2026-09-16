<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Safely delete a model with foreign key constraint handling
     *
     * @param Model $model
     * @param string $redirectRoute
     * @param array $redirectParams
     * @param string|null $parentMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function safeDelete(Model $model, string $redirectRoute, array $redirectParams = [], ?string $parentMessage = null): \Illuminate\Http\RedirectResponse
    {
        $modelName = class_basename(get_class($model));
        $relatedInfo = $this->getRelatedRecordsInfo($model);

        // Check for related records before attempting deletion
        if (!empty($relatedInfo)) {
            $details = implode(', ', $relatedInfo);
            return redirect()->back()
                ->with('warning', "Cannot delete this {$modelName} because it has the following related records: {$details}. Please remove all associated records first.");
        }

        try {
            $model->delete();
            return redirect()->route($redirectRoute, $redirectParams)
                ->with('success', $parentMessage ?? 'Record deleted successfully.');
        } catch (QueryException $e) {
            // Check for foreign key constraint violation (SQLSTATE 23000)
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'foreign key constraint')) {
                return redirect()->back()
                    ->with('warning', "Cannot delete this {$modelName} because it has related records in the database. Please remove all associated records first.");
            }
            throw $e;
        }
    }

    /**
     * Get information about related records for a model
     *
     * @param Model $model
     * @return array
     */
    protected function getRelatedRecordsInfo(Model $model): array
    {
        $relatedInfo = [];
        $modelName = class_basename(get_class($model));

        // Define relationship checks for each model type
        $relationshipMap = [
            'Customer' => [
                'customerOrders' => 'Customer Orders',
            ],
            'Supplier' => [
                'customerOrders' => 'Customer Orders',
            ],
            'CustomerOrder' => [
                'factoryOrder' => 'Factory Order',
                'payments' => 'Payments',
                'invoices' => 'Invoices',
            ],
            'FactoryOrder' => [
                'followup' => 'Factory Follow-up',
            ],
            'User' => [
                'payments' => 'Payments',
                'salaries' => 'Salaries',
            ],
            'ChartOfAccount' => [
                'journalEntryItems' => 'Journal Entries',
                'budgets' => 'Budgets',
            ],
            'OfficeAccount' => [
                'transactions' => 'Transactions',
                'journalEntries' => 'Journal Entries',
            ],
            'Invoice' => [
                'payments' => 'Payments',
                'journalEntries' => 'Journal Entries',
            ],
            'Payment' => [
                'journalEntry' => 'Journal Entry',
            ],
            'Salary' => [
                'journalEntries' => 'Journal Entries',
            ],
            'Expense' => [
                'journalEntries' => 'Journal Entries',
            ],
            'Budget' => [],
            'MarketingCampaign' => [
                'videos' => 'Videos',
                'posters' => 'Posters',
            ],
            'Lead' => [],
            'JournalEntry' => [],
        ];

        if (!isset($relationshipMap[$modelName])) {
            return [];
        }

        foreach ($relationshipMap[$modelName] as $relation => $label) {
            if (method_exists($model, $relation)) {
                $count = $model->$relation()->count();
                if ($count > 0) {
                    $relatedInfo[] = "{$count} {$label}";
                }
            }
        }

        return $relatedInfo;
    }
}
