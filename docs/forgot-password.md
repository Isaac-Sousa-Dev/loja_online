# Forgot Password — Implementação

## Fluxo

```
POST /api/auth/forgot-password  →  envia e-mail com link
POST /api/auth/reset-password   →  valida token e redefine senha
```

---

## Passo 1 — Solicitar link (`forgot-password`)

O usuário informa o e-mail. O sistema:

1. Busca o usuário — **se não existir, retorna a mesma resposta de sucesso** (anti-enumeração: não revela quais e-mails estão cadastrados).
2. Gera um token seguro via `Password::getRepository()->create()` e salva-o **hasheado** na tabela `password_reset_tokens`.
3. Monta a URL do frontend: `FRONTEND_URL/reset-password?token=...&email=...`
4. Envia o e-mail `ForgotPasswordMail` com o link.
5. Token expira em **60 minutos** (configurado em `config/auth.php`).

---

## Passo 2 — Redefinir senha (`reset-password`)

O frontend envia `token`, `email`, `password` e `password_confirmation`. O sistema:

1. Valida os campos via `ResetPasswordRequest`.
2. Chama `Password::reset()`, que verifica: token válido, token não expirado e e-mail bate com o token.
3. Se válido, salva a nova senha hasheada e **apaga o token** da tabela (uso único).
4. Retorna `200` com mensagem de sucesso, ou `400` se o token for inválido/expirado.

---

## Boas práticas aplicadas

| Prática | Como foi aplicado |
|---|---|
| Anti-enumeração | Resposta genérica em `forgot-password`, independente de o e-mail existir |
| Token hasheado | Laravel armazena o token com `bcrypt` na tabela `password_reset_tokens` |
| Expiração | Token expira em 60 min (`config/auth.php → passwords.users.expire`) |
| Uso único | `Password::reset()` apaga o token após uso bem-sucedido |
| Rate limiting | Rota protegida com `throttle:5,1` (5 tentativas por minuto por IP) |
| Senha mínima | Mínimo de 8 caracteres com confirmação obrigatória |
| Separação de responsabilidades | Request → Controller → Service, seguindo o padrão do projeto |

---

## Arquivos criados/alterados

```
app/Http/Controllers/Api/AuthController.php   — 2 novos métodos: forgotPassword, resetPassword
app/Http/Requests/Auth/ForgotPasswordRequest.php — novo
app/Http/Requests/Auth/ResetPasswordRequest.php  — novo
app/Mail/ForgotPasswordMail.php                  — novo
app/Services/AuthService.php                  — 2 novos métodos: sendPasswordResetLink, resetPassword
resources/views/emails/forgot_password.blade.php — novo
routes/api.php                                — 2 novas rotas
config/app.php                                — adicionado frontend_url
.env                                          — adicionado FRONTEND_URL
```

---

## Variável de ambiente

```env
FRONTEND_URL=https://app.seudominio.com
```

Essa URL é usada para montar o link enviado no e-mail. Se não configurada, usa `APP_URL`.
