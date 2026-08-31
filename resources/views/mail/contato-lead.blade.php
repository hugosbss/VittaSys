<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Novo contato pelo site</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f6f4ef; margin: 0; padding: 24px; }
        .card { background: #ffffff; max-width: 560px; margin: 0 auto; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .brand { font-size: 20px; font-weight: 800; color: #1f2937; }
        .brand span { color: #0ea5a4; }
        .title { font-size: 18px; font-weight: 700; color: #1f2937; margin: 8px 0 16px; }
        .field { margin-bottom: 14px; }
        .field .label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .04em; }
        .field .value { font-size: 15px; color: #1f2937; margin-top: 2px; }
        .footer { font-size: 12px; color: #94a3b8; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <p class="brand">VittaSys <span>Pharma</span></p>
        <p class="title">Recebemos um novo contato pelo site</p>

        <div class="field">
            <div class="label">Nome</div>
            <div class="value">{{ $nome }}</div>
        </div>

        <div class="field">
            <div class="label">E-mail</div>
            <div class="value">{{ $email }}</div>
        </div>

        @if ($telefone)
            <div class="field">
                <div class="label">Telefone</div>
                <div class="value">{{ $telefone }}</div>
            </div>
        @endif

        <div class="field">
            <div class="label">Mensagem</div>
            <div class="value">{{ $mensagem }}</div>
        </div>

        <p class="footer">Este e-mail foi enviado pelo formulario de contato do site VittaSys.</p>
    </div>
</body>
</html>
