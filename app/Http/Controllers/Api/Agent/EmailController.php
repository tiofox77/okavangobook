<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Envio de email transacional pelo SMTP configurado no site (escopo email:send).
 *
 * Salvaguardas contra abuso: no máximo 5 destinatários por pedido (não é
 * um motor de campanhas), limite de 30 envios por hora por token, sem
 * anexos, e cada envio — ou tentativa falhada — fica registado na auditoria
 * com o motivo (X-Reason) declarado pelo agente.
 */
class EmailController extends Controller
{
    private const MAX_DESTINATARIOS = 5;
    private const MAX_POR_HORA = 30;

    public function __construct(private AgentAuditService $audit) {}

    /** Configuração SMTP em uso (nunca expõe a palavra-passe). */
    public function config()
    {
        $mailer = config('mail.default');

        return response()->json([
            'data' => [
                'mailer' => $mailer,
                'host' => config("mail.mailers.$mailer.host"),
                'port' => config("mail.mailers.$mailer.port"),
                'encryption' => config("mail.mailers.$mailer.encryption"),
                'username_definido' => (bool) config("mail.mailers.$mailer.username"),
                'password_definida' => (bool) config("mail.mailers.$mailer.password"),
                'from' => [
                    'address' => config('mail.from.address'),
                    'name' => config('mail.from.name'),
                ],
                'contacto_do_site' => Setting::get('contact_email'),
                'limites' => [
                    'destinatarios_por_pedido' => self::MAX_DESTINATARIOS,
                    'envios_por_hora' => self::MAX_POR_HORA,
                ],
            ],
        ]);
    }

    public function send(Request $request)
    {
        $dados = $request->validate([
            'to' => ['required'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:20000'],
            'html' => ['sometimes', 'boolean'],
            'reply_to' => ['sometimes', 'nullable', 'email'],
            'cc' => ['sometimes', 'array', 'max:' . self::MAX_DESTINATARIOS],
            'cc.*' => ['email'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);

        $destinatarios = array_values(array_filter(array_map(
            'trim',
            is_array($dados['to']) ? $dados['to'] : explode(',', (string) $dados['to'])
        )));

        if ($destinatarios === []) {
            return response()->json(['message' => 'Indique pelo menos um destinatário.'], 422);
        }

        if (count($destinatarios) > self::MAX_DESTINATARIOS) {
            return response()->json([
                'message' => 'Máximo de ' . self::MAX_DESTINATARIOS . ' destinatários por pedido (esta API não faz envios em massa).',
            ], 422);
        }

        foreach ($destinatarios as $email) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['message' => "Destinatário inválido: $email"], 422);
            }
        }

        $dryRun = (bool) ($dados['dry_run'] ?? false);
        $comoHtml = (bool) ($dados['html'] ?? false);

        $resumo = [
            'to' => $destinatarios,
            'cc' => $dados['cc'] ?? [],
            'subject' => $dados['subject'],
            'html' => $comoHtml,
            'reply_to' => $dados['reply_to'] ?? null,
            'body_preview' => mb_substr(strip_tags($dados['body']), 0, 160),
        ];

        if ($dryRun) {
            $this->audit->record($request, 'email.sent', Setting::class, null, $resumo, 200, true);

            return response()->json([
                'data' => $resumo,
                'dry_run' => true,
                'message' => 'Pré-visualização; nenhum email foi enviado.',
            ]);
        }

        // Limite por token: impede que a API seja usada como motor de spam
        $token = $request->attributes->get('agentToken');
        $chave = 'agent-email:' . ($token->id ?? 'desconhecido');

        if (RateLimiter::tooManyAttempts($chave, self::MAX_POR_HORA)) {
            return response()->json([
                'message' => 'Limite de envios por hora atingido.',
                'retry_after_seconds' => RateLimiter::availableIn($chave),
            ], 429);
        }

        try {
            Mail::send([], [], function ($mensagem) use ($destinatarios, $dados, $comoHtml) {
                $mensagem->to($destinatarios)->subject($dados['subject']);

                if (! empty($dados['cc'])) {
                    $mensagem->cc($dados['cc']);
                }
                if (! empty($dados['reply_to'])) {
                    $mensagem->replyTo($dados['reply_to']);
                }

                $comoHtml
                    ? $mensagem->html($dados['body'])
                    : $mensagem->text($dados['body']);
            });
        } catch (\Throwable $e) {
            $this->audit->record($request, 'email.failed', Setting::class, null, $resumo + ['erro' => $e->getMessage()], 502, false);

            return response()->json([
                'message' => 'Falha ao enviar pelo SMTP.',
                'error' => $e->getMessage(),
            ], 502);
        }

        RateLimiter::hit($chave, 3600);
        $this->audit->record($request, 'email.sent', Setting::class, null, $resumo, 200, false);

        return response()->json([
            'data' => $resumo,
            'dry_run' => false,
            'message' => 'Email enviado.',
        ]);
    }
}
