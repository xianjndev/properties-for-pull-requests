<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{

    public function updated(Project $model): void
    {
        $dirtyAttributes = $model->getDirty();

        foreach ($dirtyAttributes as $attr => $newValue) {
            $originalValue = $model->getOriginal($attr);

            // Compare and log changes for nested or simple attributes
            $this->logChange($model, $attr, $originalValue, $newValue);
        }
    }
    private function logChange(Project $model, $attr, $originalValue, $newValue)
    {
        // Decode JSON to array if necessary
        $originalValue = $this->normalizeToArray($originalValue);
        $newValue = $this->normalizeToArray($newValue);


        // If values are arrays, flatten them to find and log specific changes
        if (is_array($originalValue) && is_array($newValue)) {
            $this->logArrayChanges($model, $attr, $originalValue, $newValue);
        } elseif ($originalValue !== $newValue) {
            // Log simple attribute changes
            $this->createLog($model, $originalValue, $newValue, $attr);
        }
    }

    private function logArrayChanges(Project $model, $attr, array $original, array $new)
    {
        $allKeys = array_unique(array_merge(array_keys($original), array_keys($new)));

        foreach ($allKeys as $key) {
            $origValue = $original[$key] ?? null;
            $newValue = $new[$key] ?? null;

            // Check if both values are arrays
            if (is_array($origValue) && is_array($newValue)) {

                // Recursively log changes for nested arrays
                $this->logArrayChanges($model, "$attr.$key", $origValue, $newValue);
            } elseif ($origValue !== $newValue) {
//                if($attr=='order'){
//                    dd($allKeys,$original,$new,$origValue,$newValue,"$attr.$key");
//                }
                // Log the specific change
                $this->createLog($model, $origValue, $newValue, "$attr.$key");
            }
        }
    }


    private function createLog(Project $model, $from, $to, $field)
    {
        if ($field=='updated_at'){
            return ;
        }

        $model->updateLogs()->create([
            'user_id'    => auth()->id(),
            'field' => $field,
            'from' => $this->formatValue($from),
            'to' => $this->formatValue($to),
        ]);
    }

    /**
     * Format value for logging.
     *
     * @param  mixed  $value
     * @return string|null
     */
    private function formatValue($value)
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return $value;
    }

    /**
     * Normalize a value to an array.
     *
     * @param mixed $value
     * @return array
     */
    private function normalizeToArray($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $value;
        }

        return $value;
    }
}
