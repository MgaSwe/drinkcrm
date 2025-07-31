<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class JiraClient
{
    protected string $baseUrl;
    protected string $token;
    protected string $projectKey;
    protected string $issueType;

    public function __construct()
    {
        $this->baseUrl    = rtrim(config('services.jira.base_url', env('JIRA_BASE_URL')), '/');
        $this->token      = config('services.jira.token', env('JIRA_TOKEN'));
        $this->projectKey = config('services.jira.project_key', env('JIRA_PROJECT_KEY', 'DRNK'));
        $this->issueType  = config('services.jira.issue_type', env('JIRA_ISSUE_TYPE', 'Task'));
    }

    public function createIssue(string $summary, string $description, ?string $projectKey = null, ?string $issueType = null, array $extraFields = []): array {
        $projectKey = $projectKey ?: $this->projectKey;
        $issueType  = $issueType  ?: $this->issueType;

        $payload = [
            'fields' => array_merge([
                'project'     => ['key' => $projectKey],
                'summary'     => $summary,
                'issuetype'   => ['name' => $issueType],
                'description' => $description,
            ], $extraFields),
        ];

        $resp = Http::withToken($this->token)
            ->acceptJson()
            ->post("{$this->baseUrl}/rest/api/2/issue", $payload);

        if ($resp->failed()) {
            throw new RequestException($resp);
        }

        return $resp->json();
    }

}
