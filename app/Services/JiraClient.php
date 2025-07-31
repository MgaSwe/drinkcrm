<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class JiraClient
{
    private string $base;
    private string $api = '/rest/api/2'; // DC

    public function __construct()
    {
        $this->base = rtrim((string) config('services.jira.base_url'), '/');
    }

    private function req(): PendingRequest
    {
        $req = Http::acceptJson();
        if (config('services.jira.auth') === 'pat') {
            $req = $req->withToken((string) config('services.jira.token')); // Bearer
        } else {
            $req = $req->withBasicAuth(
                (string) config('services.jira.user'),
                (string) config('services.jira.token')
            );
        }
        return $req;
    }

    public function createIssue(string $summary, string $description, array $fields = []): array
    {
        $payload = [
            'fields' => array_merge([
                'project'   => ['key' => (string) config('services.jira.project_key')],
                'summary'   => $summary,
                'issuetype' => ['name' => (string) config('services.jira.issue_type', 'Task')],
                'description' => $description,
            ], $fields),
        ];

        return $this->req()->post("{$this->base}{$this->api}/issue", $payload)->throw()->json();
    }

    public function comment(string $issueKey, string $text): void
    {
        $this->req()->post("{$this->base}{$this->api}/issue/{$issueKey}/comment", ['body' => $text])->throw();
    }

    public function transitionByName(string $issueKey, string $name): void
    {
        $res = $this->req()->get("{$this->base}{$this->api}/issue/{$issueKey}/transitions")->throw()->json();
        $id  = collect($res['transitions'] ?? [])->first(fn($t) => $t['name'] === $name)['id'] ?? null;
        if ($id) {
            $this->req()->post("{$this->base}{$this->api}/issue/{$issueKey}/transitions", ['transition' => ['id' => $id]])->throw();
        }
    }

    public function addRemoteLink(string $issueKey, string $url, string $title): void
    {
        $this->req()->post("{$this->base}{$this->api}/issue/{$issueKey}/remotelink", [
            'object' => ['url' => $url, 'title' => $title],
        ])->throw();
    }
}
