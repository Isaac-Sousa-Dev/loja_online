<!DOCTYPE html>
<html>
<head>
    <title>Código de Verificação</title>
</head>
<body style="background-color: #007BFF; margin: 0; padding: 0;">
    <div style="display: flex; justify-content: center; align-items: center; background-color: #007BFF; padding: 20px 0;">
        <img src="/img/logos/logo.png" style="border-radius: 10px" width="150" alt="Logo Motiv">
    </div>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 0px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="font-family: Arial, Helvetica, sans-serif; color: #007BFF; font-size: 24px; font-weight: bold;">
                Seja bem-vindo(a) à Motiv App! 🚀
            </h1>
        </div>
        <div style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 20px; color: #333;">
            <p style="margin-bottom: 16px;">Olá <strong>{{ $data['user']->name }}</strong>,</p>

            <p style="margin-bottom: 16px;">
                Que bom ter você com a gente! Para acessar sua conta na Motiv App, utilize o código de verificação exclusivo abaixo:
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <h3 style="color: #333; font-size: 18px; margin-bottom: 10px;">🔐 Seu código de verificação:</h3>
                <div style="font-size: 32px; font-weight: bold; color: #007BFF;">
                    {{ $data['user']->verification_code }}
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                <strong>Importante:</strong> este código é pessoal e intransferível. Por segurança, não compartilhe com ninguém – nem mesmo com a equipe da Motiv. Nós <strong>nunca</strong> pediremos este código.
            </p>

            <div style="text-align: center;">
                <a href="{{ route('login', ['first_login' => '1']) }}" style="background-color: #007BFF; color: white; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-size: 16px; display: inline-block; margin-top: 20px;">
                    Acessar minha conta
                </a>
            </div>
        </div>
        <div style="text-align: center; font-size: 14px; color: #777; font-family: Arial, Helvetica, sans-serif;">
            <p style="margin: 0;">Se você não solicitou este código, pode ignorar este e-mail com segurança.</p>
            <p style="margin: 0;">Caso tenha dúvidas, entre em contato com nossa equipe de suporte.</p>
        </div>
    </div>
    <div style="display: flex; justify-content: center; align-items: center; background-color: #007BFF; padding: 20px 0;">
        {{-- Rodapé opcional com logo ou informações --}}
    </div>
</body>
</html>
