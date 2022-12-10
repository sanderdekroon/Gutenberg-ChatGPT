<?php

declare(strict_types=1);

namespace Sanderdekroon\GutenbergGPT\ChatGPT;

use Tectalic\OpenAi\ClientException;
use Sanderdekroon\GutenbergGPT\Container;
use Tectalic\OpenAi\Handlers\Completions;
use Sanderdekroon\GutenbergGPT\Support\Request;
use Tectalic\OpenAi\Models\Completions\CreateRequest;
use Sanderdekroon\GutenbergGPT\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected Request $request;
    protected string $ajaxNonceAction = 'executeGPTPrompt';

    public function __construct(Container $container, Request $request)
    {
        $this->request = $request;

        parent::__construct($container);
    }

    public function register(): void
    {
        add_action('wp_ajax_executeGPTPrompt', [$this, 'executePrompt']);
        add_filter('sdk-guteberggpt_script_data', [$this, 'addAjaxNonce']);
    }

    /**
     * Callback method for the executeGPTPrompt ajax action.
     * @return null
     */
    public function executePrompt()
    {
        if ($this->requestHasValidNonce() === false) {
            return \wp_send_json_error(['message' => 'Your session has expired']);
        }

        [$prompt, $model, $max_tokens, $temperature] = $this->getRequestVariables();

        if (empty($prompt)) {
            return \wp_send_json_error(['message' => 'No valid prompt given']);
        }

        try {
            $response = $this->container->get(Completions::class)->create(
                new CreateRequest(compact('prompt', 'model', 'max_tokens', 'temperature'))
            )->toModel();
        } catch (ClientException $e) {
            return \wp_send_json_error(['message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return \wp_send_json_error(['message' => 'An internal server error occurred']);
        }

        if (empty($response->choices)) {
            return \wp_send_json_error([
                'message' => 'No response was returned',
                'response' => $response
            ]);
        }

        return \wp_send_json_success(array_merge(
            ['message' => nl2br(htmlentities(reset($response->choices)->text))],
            compact('prompt', 'model', 'max_tokens', 'temperature')
        ));
    }

    public function addAjaxNonce(array $data): array
    {
        return array_merge($data, [
            'executeGPTPrompt_nonce' => wp_create_nonce($this->ajaxNonceAction)
        ]);
    }

    protected function requestHasValidNonce(): bool
    {
        $nonce = $this->request->get('_nonce', '');

        return wp_verify_nonce($nonce, $this->ajaxNonceAction) !== false;
    }

    protected function getRequestVariables(): array
    {
        return [
            \sanitize_text_field($this->request->get('prompt', '')),
            \sanitize_text_field($this->request->get('model', 'text-davinci-003')),
            $this->request->getInt('maxTokens', 2048),
            (float) ($this->request->get('temperature', 1)),
        ];
    }
}
