@extends('layouts.app')

@section('title', 'Entrar - ' . $appName)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-teal-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-600/20 to-teal-600/20"></div>
        <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <polygon fill="url(#gradient)" points="0,0 100,0 100,80 0,100" opacity="0.1"/>
            <defs>
                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#3B82F6"/>
                    <stop offset="100%" stop-color="#14B8A6"/>
                </linearGradient>
            </defs>
        </svg>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="floating-icon absolute top-20 left-10 text-blue-300 opacity-30">
            <i class="fas fa-hotel text-3xl animate-bounce" style="animation-delay: 0s;"></i>
        </div>
        <div class="floating-icon absolute top-40 right-20 text-teal-300 opacity-30">
            <i class="fas fa-plane text-2xl animate-bounce" style="animation-delay: 1s;"></i>
        </div>
        <div class="floating-icon absolute bottom-40 left-20 text-blue-400 opacity-30">
            <i class="fas fa-bed text-2xl animate-bounce" style="animation-delay: 2s;"></i>
        </div>
        <div class="floating-icon absolute bottom-20 right-10 text-teal-400 opacity-30">
            <i class="fas fa-umbrella-beach text-3xl animate-bounce" style="animation-delay: 0.5s;"></i>
        </div>
    </div>

    <div class="max-w-6xl w-full space-y-8 relative z-10">
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            <div class="lg:flex">
                <!-- Left Panel - Visual/Branding -->
                <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-teal-600 p-12 flex flex-col justify-center relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-24 -translate-x-24"></div>
                    
                    <div class="relative z-10 text-center lg:text-left">
                        <!-- Logo/Brand -->
                        <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-4 backdrop-blur-sm">
                                <i class="fas fa-hotel text-3xl text-white"></i>
                            </div>
                            <h1 class="text-4xl font-bold text-white mb-2">{{ $appName }}</h1>
                            <p class="text-blue-100 text-lg">Sua experiência única começa aqui</p>
                        </div>
                        
                        <!-- Features -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center text-white/90 transform hover:translate-x-2 transition-transform duration-300">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-star text-sm"></i>
                                </div>
                                <span>Hotéis e resorts exclusivos</span>
                            </div>
                            <div class="flex items-center text-white/90 transform hover:translate-x-2 transition-transform duration-300">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-shield-alt text-sm"></i>
                                </div>
                                <span>Reservas seguras e confiáveis</span>
                            </div>
                            <div class="flex items-center text-white/90 transform hover:translate-x-2 transition-transform duration-300">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-sm"></i>
                                </div>
                                <span>Suporte 24/7</span>
                            </div>
                        </div>
                        
                        <!-- Quote -->
                        <blockquote class="text-blue-100 italic text-lg relative">
                            <i class="fas fa-quote-left text-white/30 text-3xl absolute -top-4 -left-2"></i>
                            <p class="pl-8">"Descubra paisagens deslumbrantes e experiências inesquecíveis em Angola"</p>
                        </blockquote>
                    </div>
                </div>

                <!-- Right Panel - Login Form -->
                <div class="lg:w-1/2 p-12 bg-white">
                    <div class="max-w-md mx-auto">
                        <!-- Header -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl mb-4 shadow-lg transform hover:scale-110 transition-all duration-300">
                                <i class="fas fa-sign-in-alt text-white text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Bem-vindo de volta!</h2>
                            <p class="text-gray-600">Entre na sua conta para continuar sua jornada</p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                            @csrf
                            
                            <!-- Email Field -->
                            <div class="group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                    <i class="fas fa-envelope mr-2"></i>Email
                                </label>
                                <div class="relative">
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        autocomplete="email" 
                                        required 
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-gray-50 focus:bg-white group-hover:border-gray-400 @error('email') border-red-500 @enderror"
                                        placeholder="seu@email.com"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="fas fa-user text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 animate-pulse">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="group">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2 transition-colors group-focus-within:text-blue-600">
                                    <i class="fas fa-lock mr-2"></i>Senha
                                </label>
                                <div class="relative">
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        autocomplete="current-password" 
                                        required
                                        class="w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-gray-50 focus:bg-white group-hover:border-gray-400 @error('password') border-red-500 @enderror"
                                        placeholder="Sua senha secreta"
                                    >
                                    <button 
                                        type="button" 
                                        id="togglePassword" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                                    >
                                        <i class="fas fa-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 animate-pulse">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        id="remember" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    <label for="remember" class="ml-2 text-sm text-gray-600 hover:text-gray-800 transition-colors cursor-pointer">
                                        Lembrar-me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors hover:underline">
                                    Esqueceu a senha?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-teal-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95"
                                id="submitBtn"
                            >
                                <span class="flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    <span id="submitText">Entrar</span>
                                    <div class="hidden ml-2" id="submitSpinner">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                    </div>
                                </span>
                            </button>

                            <!-- Register Link -->
                            <div class="text-center pt-4 border-t border-gray-200">
                                <p class="text-gray-600">
                                    Não tem uma conta? 
                                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold transition-colors hover:underline">
                                        Criar conta gratuita
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles & Scripts -->
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .floating-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    .group:hover .group-hover\:border-gray-400 {
        border-color: #9CA3AF;
    }
    
    .form-input-focus {
        transform: scale(1.02);
    }
    
    /* Smooth transitions for form interactions */
    input:focus {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Custom gradient animation */
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .bg-gradient-animated {
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    
    if (togglePassword && passwordInput && passwordIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });
    }
    
    // Form submission animation
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitText.textContent = 'Entrando...';
            submitSpinner.classList.remove('hidden');
            submitBtn.classList.add('bg-gradient-animated');
        });
    }
    
    // Input animations
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('form-input-focus');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('form-input-focus');
        });
    });
    
    // Page entrance animation
    const loginPanel = document.querySelector('.bg-white\\/95');
    if (loginPanel) {
        loginPanel.style.opacity = '0';
        loginPanel.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            loginPanel.style.transition = 'all 0.8s ease-out';
            loginPanel.style.opacity = '1';
            loginPanel.style.transform = 'translateY(0)';
        }, 100);
    }
});
</script>
@endsection
