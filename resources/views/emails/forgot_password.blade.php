<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinição de senha</title>
</head>
<body style="background-color: #007BFF; margin: 0; padding: 0;">
    <div style="display: flex; justify-content: center; align-items: center; background-color: #007BFF; padding: 20px 0;">
        <img src="/img/logos/logo.png" style="border-radius: 10px" width="150" alt="Logo">
    </div>

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 0px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="font-family: Arial, Helvetica, sans-serif; color: #007BFF; font-size: 24px; font-weight: bold;">Redefinição de senha</h1>
        </div>

        <div style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 20px;">
            <p style="margin-bottom: 16px;">Olá, <strong>{{ $user->name }}</strong>.</p>
            <p style="margin-bottom: 16px;">Recebemos uma solicitação para redefinir a senha da sua conta. Clique no botão abaixo para criar uma nova senha:</p>

            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}"
                   style="background-color: #007BFF; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: bold; display: inline-block;">
                    Redefinir minha senha
                </a>
            </div>

            <p style="margin-bottom: 16px;">Este link é válido por <strong>60 minutos</strong>. Após esse prazo, você precisará solicitar um novo link.</p>
            <p style="margin-bottom: 16px;">Se você não solicitou a redefinição de senha, ignore este e-mail. Sua senha permanece a mesma.</p>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 24px 0;">

            <p style="margin-bottom: 8px; font-size: 13px; color: #555;">Se o botão acima não funcionar, copie e cole o link abaixo no seu navegador:</p>
            <p style="word-break: break-all; font-size: 13px; color: #007BFF;">{{ $resetUrl }}</p>
        </div>

        <div style="text-align: center; font-size: 13px; color: #777; font-family: Arial, Helvetica, sans-serif;">
            <p>Este é um e-mail automático. Por favor, não responda a esta mensagem.</p>
        </div>
    </div>

    <div style="background-color: #007BFF; padding: 20px 0;"></div>
</body>
</html>
