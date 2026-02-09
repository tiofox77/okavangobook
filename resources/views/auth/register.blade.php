@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-500 via-purple-600 to-yellow-400 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Elementos flutuantes com cores vivas -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-20 h-20 bg-yellow-400 rounded-full opacity-70 animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-blue-500 rounded-full opacity-60 animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-purple-500 rounded-full opacity-50 animate-ping"></div>
        <div class="absolute bottom-40 right-10 w-12 h-12 bg-yellow-300 rounded-full opacity-80 animate-pulse"></div>
        <div class="absolute top-60 left-1/2 w-18 h-18 bg-blue-400 rounded-full opacity-60 animate-bounce"></div>
        <div class="absolute top-80 right-1/3 w-14 h-14 bg-purple-400 rounded-full opacity-70 animate-ping"></div>
    </div>

    <div class="w-full max-w-6xl mx-auto relative z-10">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-purple-200 overflow-hidden">
            <div class="flex flex-col lg:flex-row min-h-[700px]">
                <!-- Painel Esquerdo - Formulário -->
                <div class="lg:w-1/2 p-8 lg:p-12 bg-gradient-to-br from-white to-blue-50">
                    <div class="max-w-md mx-auto">
                        <!-- Cabeçalho -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-4 shadow-lg">
                                <i class="fas fa-user-plus text-white text-2xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-blue-800 mb-2">Junte-se a nós!</h2>
                            <p class="text-purple-600 font-medium">Crie sua conta e descubra experiências incríveis</p>
                        </div>

                        <!-- Formulário -->
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf

                            <!-- Nome Completo -->
                            <div>
                                <label for="name" class="flex items-center text-blue-800 font-semibold mb-2">
                                    <i class="fas fa-user text-purple-600 mr-2"></i>
                                    Nome Completo
                                </label>
                                <input id="name" type="text" 
                                       class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/90 text-blue-900 placeholder-blue-400" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autocomplete="name" 
                                       autofocus
                                       placeholder="Digite seu nome completo">
                                @error('name')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="flex items-center text-blue-800 font-semibold mb-2">
                                    <i class="fas fa-envelope text-purple-600 mr-2"></i>
                                    Email
                                </label>
                                <input id="email" type="email" 
                                       class="w-full px-4 py-3 border-2 border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/90 text-blue-900 placeholder-blue-400" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email"
                                       placeholder="seu@email.com">
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Senha -->
                            <div>
                                <label for="password" class="flex items-center text-blue-800 font-semibold mb-2">
                                    <i class="fas fa-lock text-purple-600 mr-2"></i>
                                    Senha
                                </label>
                                <div class="relative">
                                    <input id="password" type="password" 
                                           class="w-full px-4 py-3 pr-12 border-2 border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/90 text-blue-900 placeholder-blue-400" 
                                           name="password" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Mínimo 8 caracteres"
                                           onkeyup="checkPasswordStrength(this.value)">
                                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800 transition-colors duration-200" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-toggle-icon"></i>
                                    </button>
                                </div>
                                
                                <!-- Indicador de força da senha -->
                                <div class="mt-2" id="password-strength" style="display: none;">
                                    <div class="text-sm text-blue-700 mb-1">Força da senha:</div>
                                    <div class="flex space-x-1">
                                        <div class="h-2 w-1/4 bg-blue-200 rounded-full">
                                            <div class="h-full bg-red-500 rounded-full transition-all duration-300" id="strength-bar-1"></div>
                                        </div>
                                        <div class="h-2 w-1/4 bg-blue-200 rounded-full">
                                            <div class="h-full bg-yellow-500 rounded-full transition-all duration-300" id="strength-bar-2"></div>
                                        </div>
                                        <div class="h-2 w-1/4 bg-blue-200 rounded-full">
                                            <div class="h-full bg-yellow-400 rounded-full transition-all duration-300" id="strength-bar-3"></div>
                                        </div>
                                        <div class="h-2 w-1/4 bg-blue-200 rounded-full">
                                            <div class="h-full bg-green-500 rounded-full transition-all duration-300" id="strength-bar-4"></div>
                                        </div>
                                    </div>
                                    <div class="text-xs mt-1 font-medium" id="strength-text"></div>
                                </div>
                                
                                <div class="text-xs text-purple-600 mt-1">
                                    Deve conter pelo menos 8 caracteres, incluindo maiúsculas, minúsculas e números
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirmar Senha -->
                            <div>
                                <label for="password_confirmation" class="flex items-center text-blue-800 font-semibold mb-2">
                                    <i class="fas fa-lock text-purple-600 mr-2"></i>
                                    Confirmar Senha
                                </label>
                                <div class="relative">
                                    <input id="password_confirmation" type="password" 
                                           class="w-full px-4 py-3 pr-12 border-2 border-blue-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 bg-white/90 text-blue-900 placeholder-blue-400" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Confirme sua senha"
                                           onkeyup="checkPasswordMatch()">
                                    <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-600 hover:text-purple-800 transition-colors duration-200" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-toggle-icon"></i>
                                    </button>
                                </div>
                                <div id="password-match-message" class="text-xs mt-1 hidden"></div>
                            </div>

                            <!-- Termos de Serviço -->
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" id="terms" name="terms" required 
                                       class="mt-1 w-4 h-4 text-purple-600 bg-white border-2 border-blue-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="terms" class="text-sm text-blue-700 leading-relaxed">
                                    Concordo com os 
                                    <a href="#" class="text-purple-600 hover:text-purple-800 underline font-medium">Termos de Serviço</a> 
                                    e 
                                    <a href="#" class="text-purple-600 hover:text-purple-800 underline font-medium">Política de Privacidade</a>
                                </label>
                            </div>

                            <!-- Botão de Submissão -->
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 hover:from-yellow-500 hover:via-yellow-600 hover:to-yellow-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center justify-center space-x-2 border-2 border-yellow-300"
                                    id="submit-btn">
                                <span id="submit-text">Criar Conta</span>
                                <i class="fas fa-arrow-right" id="submit-icon"></i>
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white hidden" id="submit-spinner"></div>
                            </button>
                        </form>

                        <!-- Link para Login -->
                        <div class="text-center mt-6">
                            <p class="text-blue-700">
                                Já tem uma conta? 
                                <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-bold underline transition-colors duration-200">
                                    Faça login aqui
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Painel Direito - Branding -->
                <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-purple-600 to-yellow-500 text-white p-8 lg:p-12 flex flex-col justify-center relative overflow-hidden">
                    <!-- Elementos decorativos -->
                    <div class="absolute top-10 right-10 w-32 h-32 bg-yellow-400/20 rounded-full"></div>
                    <div class="absolute bottom-10 left-10 w-24 h-24 bg-blue-400/20 rounded-full"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-purple-400/10 rounded-full"></div>

                    <div class="relative z-10">
                        <!-- Título -->
                        <div class="text-center mb-8">
                            <h2 class="text-4xl font-bold mb-4 text-shadow-lg">Sua próxima aventura começa aqui</h2>
                            <div class="w-20 h-1 bg-yellow-400 mx-auto rounded-full"></div>
                        </div>

                        <!-- Benefícios -->
                        <div class="space-y-6 mb-8">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-map-marked-alt text-blue-800 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Destinos Únicos</h3>
                                    <p class="text-blue-100">Explore lugares incríveis e experiências autênticas</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-purple-400 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Comunidade Vibrante</h3>
                                    <p class="text-blue-100">Conecte-se com outros aventureiros apaixonados</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-star text-yellow-300 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg">Experiências 5 Estrelas</h3>
                                    <p class="text-blue-100">Qualidade garantida em cada reserva</p>
                                </div>
                            </div>
                        </div>

                        <!-- Depoimento -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-400 text-lg">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-white italic text-lg leading-relaxed">
                                "Plataforma incrível! Encontrei o resort perfeito para minhas férias. 
                                O processo de reserva foi super fácil e o atendimento excepcional."
                            </p>
                            <div class="mt-4 flex items-center">
                                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-800"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold">Maria Silva</p>
                                    <p class="text-sm text-blue-100">Cliente Satisfeita</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle de visibilidade da senha
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-toggle-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Verificador de força da senha
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('password-strength');
        const strengthText = document.getElementById('strength-text');
        const bars = ['strength-bar-1', 'strength-bar-2', 'strength-bar-3', 'strength-bar-4'];
        
        if (password.length === 0) {
            strengthDiv.style.display = 'none';
            return;
        }
        
        strengthDiv.style.display = 'block';
        
        let strength = 0;
        
        // Critérios de força
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Limpar barras
        bars.forEach(bar => {
            document.getElementById(bar).style.width = '0%';
        });
        
        // Preencher barras baseado na força
        for (let i = 0; i < Math.min(strength, 4); i++) {
            document.getElementById(bars[i]).style.width = '100%';
        }
        
        // Texto de feedback
        const messages = [
            { text: 'Muito Fraca', color: 'text-red-600' },
            { text: 'Fraca', color: 'text-red-500' },
            { text: 'Média', color: 'text-yellow-600' },
            { text: 'Forte', color: 'text-yellow-500' },
            { text: 'Muito Forte', color: 'text-green-600' }
        ];
        
        const message = messages[Math.min(strength, 4)];
        strengthText.textContent = message.text;
        strengthText.className = `text-xs mt-1 font-medium ${message.color}`;
    }

    // Verificador de confirmação de senha
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        const messageDiv = document.getElementById('password-match-message');
        
        if (confirmation.length === 0) {
            messageDiv.classList.add('hidden');
            return;
        }
        
        messageDiv.classList.remove('hidden');
        
        if (password === confirmation) {
            messageDiv.textContent = '✓ Senhas coincidem';
            messageDiv.className = 'text-xs mt-1 text-green-600 font-medium';
        } else {
            messageDiv.textContent = '✗ Senhas não coincidem';
            messageDiv.className = 'text-xs mt-1 text-red-600 font-medium';
        }
    }

    // Animação do botão de envio no submit do formulário (não no click)
    document.querySelector('form').addEventListener('submit', function(e) {
        // Não prevenir o envio padrão do formulário
        const btn = document.getElementById('submit-btn');
        const text = document.getElementById('submit-text');
        const icon = document.getElementById('submit-icon');
        const spinner = document.getElementById('submit-spinner');
        
        // Animar botão
        text.textContent = 'Criando conta...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        // NÃO desabilitar o botão, pois isso pode impedir o envio
        // btn.disabled = true;
        btn.classList.add('opacity-75');
        
        // Permitir que o formulário continue seu envio normal
        return true;
    });
</script>

<style>
    .text-shadow-lg {
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-bounce {
        animation: bounce 1s infinite;
    }
    
    .animate-ping {
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateY(-25%);
            animation-timing-function: cubic-bezier(0.8,0,1,1);
        }
        50% {
            transform: none;
            animation-timing-function: cubic-bezier(0,0,0.2,1);
        }
    }
    
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
</style>
@endsection
