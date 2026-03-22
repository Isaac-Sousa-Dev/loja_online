<!DOCTYPE html>
<html>
<head>
    <title>Boas-vindas</title>
</head>
<body style="background-color: #007BFF; margin: 0; padding: 0;">
    <div style="display: flex; justify-content: center; align-items: center; background-color: #007BFF; padding: 20px 0;">
        <img src="/img/logos/logo.png" style="border-radius: 10px" width="150" alt="">
    </div>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 0px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="font-family: Arial, Helvetica, sans-serif; color: #007BFF; font-size: 24px; font-weight: bold;">Seja bem-vindo(a) à Motiv App!</h1>
        </div>
        <div style="font-family: Arial, Helvetica, sans-serif; margin-bottom: 20px;">
            <p style="margin-bottom: 16px;">Oi <strong>{{$user['name']}}</strong>,</p>
            <p style="margin-bottom: 16px;">Muito obrigado por se cadastrar na Motiv App! Ficamos super felizes em ter você por aqui. 😊</p>
            <p style="margin-bottom: 16px;">Agora que você fez o cadastro, nossa equipe vai entrar em contato com você em breve para finalizar o pagamento e liberar seu acesso ao sistema.</p>
            <p style="margin-bottom: 16px;">Enquanto isso, dá uma olhada no que você vai ganhar usando a Motiv App:</p>
            <ul style="list-style-type: disc; padding-left: 20px; margin-bottom: 16px;">
                <li style="margin-bottom: 8px;"><strong>Catálogo Online:</strong> Um site profissional e moderno para sua loja online.</li>
                <li style="margin-bottom: 8px;"><strong>Gestão de Produtos:</strong> Cadastre e gerencie seus produtos de um jeito super fácil.</li>
                <li style="margin-bottom: 8px;"><strong>CRM Dedicado:</strong> Gerencie seus clientes e aumente suas vendas.</li>
                {{-- <li style="margin-bottom: 8px;"><strong>Indique e Ganhe:</strong> Indique a Motiv e ganhe descontos na sua mensalidade.</li> --}}
            </ul>
            <p style="margin-bottom: 16px;">Fica de olho no seu telefone e e-mail, porque nossa equipe vai entrar em contato em breve para liberar seu acesso, ok?</p>
            <p style="margin-bottom: 16px;">Se tiver qualquer dúvida ou precisar de ajuda, é só mandar um e-mail para <a href="mailto:seu-email@suporte.com" style="color: #007BFF; text-decoration: underline;">motiv@suporte.com</a>.</p>
            <p style="margin-bottom: 16px;">Estamos super animados para ajudar sua loja a crescer ainda mais!</p>
            <p>Abraços,</p>
            <p style="font-weight: bold;">Equipe Motiv App</p>
        </div>
        <div style="text-align: center; font-size: 14px; color: #777; font-family: Arial, Helvetica, sans-serif;">
            <p>Este é um e-mail automático. Por favor, não responda a esta mensagem.</p>
        </div>
    </div>
    <div style="display: flex; justify-content: center; align-items: center; background-color: #007BFF; padding: 20px 0;">
        {{-- <img src="/img/logos/logo.png" style="border-radius: 10px" width="150" alt=""> --}}
    </div>
</body>
</html>