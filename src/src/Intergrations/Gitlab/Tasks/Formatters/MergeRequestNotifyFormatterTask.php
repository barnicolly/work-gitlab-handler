<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks\Formatters;

class MergeRequestNotifyFormatterTask
{
    public function run(array $mergeRequests, string $title): string
    {
        $template = '<a href="%s">%s</a>';
        $html = "<b>{$title}:</b>" . PHP_EOL;
        foreach ($mergeRequests as $item) {
            $html .= sprintf($template, ...$item) . PHP_EOL;
        }
        return $html;
    }
}
