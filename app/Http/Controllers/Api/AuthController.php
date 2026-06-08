<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @tags Autenticação
 */
class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Registrar nova loja
     *
     * Cria um novo parceiro (usuário) e sua loja vinculada.
     *
     * @unauthenticated
     *
     * @response 201 {
     *   "message": "Loja registrada com sucesso"
     * }
     * @response 422 {
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     * @response 500 {
     *   "error": "Mensagem de erro interno"
     * }
     */
    public function registerNewStore(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6',
                'store.name'            => 'required|string|max:255',
                'store.phone'           => 'required|string|max:20',
            ]);

            $this->authService->registerNewPartnerAndStore($data);

            return response()->json(['message' => 'Loja registrada com sucesso'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Login
     *
     * Autentica o usuário e retorna o token JWT Bearer.
     *
     * @unauthenticated
     *
     * @response {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *   "token_type": "bearer",
     *   "expires_in": 3600,
     *   "user": {}
     * }
     * @response 401 {
     *   "error": "Credenciais inválidas"
     * }
     * @response 403 {
     *   "error": "Conta inativa"
     * }
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) return response()->json(['error' => 'Credenciais inválidas'], 401);

        $user = Auth::guard('api')->user();

        if (isset($user->is_active) && !$user->is_active) {
            Auth::guard('api')->logout();
            return response()->json(['error' => 'Conta inativa'], 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Logout
     *
     * Invalida o token JWT atual e encerra a sessão.
     *
     * @response {
     *   "message": "Logout realizado com sucesso"
     * }
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException) {
            // token já expirado ou inválido — logout mesmo assim
        }

        Auth::guard('api')->logout();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Usuário autenticado
     *
     * Retorna os dados do usuário dono do token JWT.
     *
     * @response {}
     */
    public function me(): JsonResponse
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * Esqueci minha senha
     *
     * Envia um link de redefinição de senha para o e-mail informado.
     * A resposta é sempre a mesma, independente de o e-mail existir ou não (anti-enumeração).
     *
     * @unauthenticated
     *
     * @response {
     *   "message": "Se o e-mail estiver cadastrado, você receberá um link de recuperação em breve."
     * }
     * @response 422 {
     *   "errors": {
     *     "email": ["The email field is required."]
     *   }
     * }
     * @response 429 {
     *   "message": "Too Many Attempts."
     * }
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->sendPasswordResetLink($request->email);

        return response()->json([
            'message' => 'Se o e-mail estiver cadastrado, você receberá um link de recuperação em breve.',
        ]);
    }

    /**
     * Redefinir senha
     *
     * Valida o token recebido por e-mail e redefine a senha do usuário.
     *
     * @unauthenticated
     *
     * @response {
     *   "message": "Senha redefinida com sucesso."
     * }
     * @response 400 {
     *   "error": "Token inválido ou expirado."
     * }
     * @response 422 {
     *   "errors": {
     *     "password": ["The password field must be at least 8 characters."]
     *   }
     * }
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request->only(
            'token', 'email', 'password', 'password_confirmation'
        ));

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(['error' => 'Token inválido ou expirado.'], 400);
        }

        return response()->json(['message' => 'Senha redefinida com sucesso.']);
    }

    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
            'user'         => Auth::guard('api')->user(),
        ]);
    }
}
