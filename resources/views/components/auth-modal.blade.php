@props([])

<div
    x-data="authModalRegister()"
    x-show="$store.auth.open"
    x-cloak
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="$store.auth.open = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-0 sm:p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="auth-modal-title"
>
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="$store.auth.open = false"></div>

    <div
        x-show="$store.auth.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-6xl max-h-[92vh] sm:max-h-[88vh] flex flex-col lg:flex-row bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden"
        @click.stop
    >
        <button
            type="button"
            @click="$store.auth.open = false"
            class="absolute top-4 right-4 z-20 w-10 h-10 flex items-center justify-center rounded-full text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
            aria-label="Cerrar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="hidden lg:flex lg:w-[42%] relative bg-neutral-900">
            <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80" alt="Eventos" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-b from-violet-700/85 via-violet-600/70 to-violet-900/80"></div>
            <div class="relative z-10 p-8 text-white flex flex-col justify-between">
                <div>
                    <h2 class="text-3xl font-bold leading-tight">Regístrate y compra entradas en segundos</h2>
                    <p class="mt-3 text-white/90 text-sm">Si eres organizador, podrás publicar eventos y vender entradas desde tu panel.</p>
                </div>
                <ul class="space-y-2 text-sm text-white/90">
                    <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-300"></span>Pago seguro</li>
                    <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-300"></span>Tickets digitales inmediatos</li>
                    <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-300"></span>Gestión de eventos para organizadores</li>
                </ul>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto min-h-0">
            <div class="p-6 sm:p-8">
                <a href="{{ route('home') }}" @click="$store.auth.open = false" class="inline-flex items-center gap-2.5 text-slate-900 font-bold text-xl mb-5">
                    <span class="w-10 h-10 rounded-xl bg-violet-600 flex items-center justify-center text-white text-lg font-extrabold">C</span>
                    ChiclayoTicket
                </a>

                <div class="mb-5">
                    <h1 id="auth-modal-title" class="text-xl sm:text-2xl font-bold text-slate-900" x-text="$store.auth.mode === 'login' ? 'Inicia sesión' : 'Crear cuenta'"></h1>
                    <p class="mt-2 text-sm text-slate-500">
                        <template x-if="$store.auth.mode === 'login'">
                            <span>¿No tienes cuenta? <button type="button" @click="$store.auth.mode = 'register'" class="font-semibold text-violet-600 hover:text-violet-700">Regístrate aquí</button></span>
                        </template>
                        <template x-if="$store.auth.mode === 'register'">
                            <span>¿Ya tienes cuenta? <button type="button" @click="$store.auth.mode = 'login'" class="font-semibold text-violet-600 hover:text-violet-700">Inicia sesión</button></span>
                        </template>
                    </p>
                </div>

                <div x-show="$store.auth.mode === 'login'" x-cloak style="display: none;" class="space-y-4">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="auth-login-email" class="block text-sm font-medium text-slate-700 mb-1.5">Correo electrónico</label>
                            <input type="email" name="email" id="auth-login-email" value="{{ old('email') }}" required autocomplete="email" placeholder="correo@ejemplo.com" class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="auth-login-password" class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                            <div class="relative">
                                <input :type="showLoginPassword ? 'text' : 'password'" name="password" id="auth-login-password" required autocomplete="current-password" placeholder="••••••••" class="w-full h-11 px-4 pr-11 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 @error('password') border-red-500 @enderror">
                                <button type="button" @click="showLoginPassword = !showLoginPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Mostrar u ocultar contraseña">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                            Recordarme
                        </label>

                        <button type="submit" class="w-full h-12 rounded-full bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors">Iniciar sesión</button>
                    </form>
                </div>

                <div x-show="$store.auth.mode === 'register'" x-cloak style="display: none;" class="space-y-4">
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <p class="text-sm font-medium text-slate-700 mb-2">Tipo de cuenta</p>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="registerRole = 'client'" :class="registerRole === 'client' ? 'bg-violet-50 border-violet-500 text-violet-700' : 'bg-white border-slate-300 text-slate-600'" class="h-11 rounded-xl border text-sm font-semibold transition-colors">Cliente</button>
                                <button type="button" @click="registerRole = 'organizer'" :class="registerRole === 'organizer' ? 'bg-violet-50 border-violet-500 text-violet-700' : 'bg-white border-slate-300 text-slate-600'" class="h-11 rounded-xl border text-sm font-semibold transition-colors">Organizador</button>
                            </div>
                            <input type="hidden" name="role" :value="registerRole">
                            @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="auth-reg-password" class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                            <div class="relative">
                                <input
                                    :type="showRegisterPassword ? 'text' : 'password'"
                                    name="password"
                                    id="auth-reg-password"
                                    x-model="registerPassword"
                                    @focus="passwordFocused = true"
                                    @blur="setTimeout(() => passwordFocused = false, 120)"
                                    required
                                    autocomplete="new-password"
                                    placeholder="••••••••"
                                    class="w-full h-11 px-4 pr-11 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 @error('password') border-red-500 @enderror"
                                >
                                <button type="button" @click="showRegisterPassword = !showRegisterPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Mostrar u ocultar contraseña">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>

                                <div x-show="passwordFocused" x-cloak class="absolute z-20 mt-2 left-0 right-0 sm:right-auto sm:w-80 bg-white border border-slate-200 rounded-xl shadow-lg p-3 text-xs text-slate-600 space-y-1">
                                    <div class="flex items-center gap-2" :class="passwordRule('length') ? 'text-emerald-600' : 'text-slate-500'"><span>●</span><span>Mínimo 8 caracteres</span></div>
                                    <div class="flex items-center gap-2" :class="passwordRule('number') ? 'text-emerald-600' : 'text-slate-500'"><span>●</span><span>Incluir un número</span></div>
                                    <div class="flex items-center gap-2" :class="passwordRule('upper') ? 'text-emerald-600' : 'text-slate-500'"><span>●</span><span>Incluir mayúscula</span></div>
                                    <div class="flex items-center gap-2" :class="passwordRule('lower') ? 'text-emerald-600' : 'text-slate-500'"><span>●</span><span>Incluir minúscula</span></div>
                                    <div class="flex items-center gap-2" :class="passwordRule('symbol') ? 'text-emerald-600' : 'text-slate-500'"><span>●</span><span>Incluir carácter especial</span></div>
                                    <p class="pt-1 text-slate-500">Especiales permitidos: @ $ ! % * ? & - _</p>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <div class="h-1.5 flex-1 rounded-full" :class="passwordScore() >= 1 ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                                <div class="h-1.5 flex-1 rounded-full" :class="passwordScore() >= 3 ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                                <div class="h-1.5 flex-1 rounded-full" :class="passwordScore() >= 5 ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                            </div>
                            <p class="mt-1 text-xs font-medium" :class="passwordScore() >= 5 ? 'text-emerald-600' : 'text-amber-600'" x-text="passwordLabel()"></p>
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="auth-password-confirm" class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar contraseña</label>
                            <div class="relative">
                                <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" id="auth-password-confirm" required autocomplete="new-password" placeholder="••••••••" class="w-full h-11 px-4 pr-11 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" aria-label="Mostrar u ocultar confirmación de contraseña">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1">Apellidos</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-slate-700 mb-1">País</label>
                                <select name="country" id="country" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                    @php $countryOld = old('country', 'Perú'); @endphp
                                    <option value="Perú" {{ $countryOld === 'Perú' ? 'selected' : '' }}>Perú</option>
                                    <option value="Chile" {{ $countryOld === 'Chile' ? 'selected' : '' }}>Chile</option>
                                    <option value="Colombia" {{ $countryOld === 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                    <option value="Ecuador" {{ $countryOld === 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                    <option value="México" {{ $countryOld === 'México' ? 'selected' : '' }}>México</option>
                                </select>
                                @error('country')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Ciudad</label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" list="city-options" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500" placeholder="Ej: Chiclayo">
                                <datalist id="city-options">
                                    <option value="Chiclayo"></option>
                                    <option value="Lima"></option>
                                    <option value="Trujillo"></option>
                                    <option value="Piura"></option>
                                    <option value="Arequipa"></option>
                                </datalist>
                                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="document_type" class="block text-sm font-medium text-slate-700 mb-1">Tipo de documento</label>
                                <select name="document_type" id="document_type" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                    @php $docType = old('document_type', 'dni'); @endphp
                                    <option value="dni" {{ $docType === 'dni' ? 'selected' : '' }}>DNI</option>
                                    <option value="ce" {{ $docType === 'ce' ? 'selected' : '' }}>Carné de extranjería</option>
                                    <option value="pasaporte" {{ $docType === 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                                @error('document_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="document_number" class="block text-sm font-medium text-slate-700 mb-1">Nro. documento</label>
                                <input type="text" name="document_number" id="document_number" value="{{ old('document_number') }}" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                @error('document_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Género</label>
                                <select name="gender" id="gender" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500">
                                    @php $genderOld = old('gender', 'prefer_not'); @endphp
                                    <option value="male" {{ $genderOld === 'male' ? 'selected' : '' }}>Hombre</option>
                                    <option value="female" {{ $genderOld === 'female' ? 'selected' : '' }}>Mujer</option>
                                    <option value="other" {{ $genderOld === 'other' ? 'selected' : '' }}>Otro</option>
                                    <option value="prefer_not" {{ $genderOld === 'prefer_not' ? 'selected' : '' }}>Prefiero no decirlo</option>
                                </select>
                                @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Teléfono</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500" placeholder="Ej: 916037806">
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div x-show="registerRole === 'organizer'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 rounded-xl border border-emerald-200 bg-emerald-50/40">
                            <div class="sm:col-span-2">
                                <h3 class="text-sm font-semibold text-emerald-700">Datos de organizador</h3>
                            </div>
                            <div>
                                <label for="organization_name" class="block text-sm font-medium text-slate-700 mb-1">Nombre comercial</label>
                                <input type="text" name="organization_name" id="organization_name" value="{{ old('organization_name') }}" :required="registerRole === 'organizer'" class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                                @error('organization_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="ruc" class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
                                <input type="text" name="ruc" id="ruc" value="{{ old('ruc') }}" :required="registerRole === 'organizer'" maxlength="11" class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500" placeholder="11 dígitos">
                                @error('ruc')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="organization_address" class="block text-sm font-medium text-slate-700 mb-1">Dirección fiscal</label>
                                <input type="text" name="organization_address" id="organization_address" value="{{ old('organization_address') }}" :required="registerRole === 'organizer'" class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                                @error('organization_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="auth-reg-email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                            <input type="email" name="email" id="auth-reg-email" value="{{ old('email') }}" required autocomplete="email" class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2 text-sm">
                            <label class="flex items-start gap-2 text-slate-600">
                                <input type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Acepto los <a href="{{ route('home') }}#terminos" class="font-semibold text-violet-600 hover:text-violet-700">Términos y condiciones</a> y la <a href="{{ route('home') }}#privacidad" class="font-semibold text-violet-600 hover:text-violet-700">Política de privacidad</a>.</span>
                            </label>
                            @error('terms_accepted')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

                            <label class="flex items-start gap-2 text-slate-600">
                                <input type="checkbox" name="marketing_consent" value="1" {{ old('marketing_consent') ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Doy mi consentimiento para recibir promociones y beneficios.</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full h-12 rounded-full bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
    function authModalRegister() {
        return {
            showLoginPassword: false,
            showRegisterPassword: false,
            showPasswordConfirm: false,
            registerRole: @js(old('role', 'client')),
            registerPassword: '',
            passwordFocused: false,
            passwordRule(rule) {
                const value = this.registerPassword || '';
                const rules = {
                    length: value.length >= 8,
                    number: /\d/.test(value),
                    upper: /[A-Z]/.test(value),
                    lower: /[a-z]/.test(value),
                    symbol: /[^A-Za-z0-9]/.test(value),
                };
                return !!rules[rule];
            },
            passwordScore() {
                return ['length', 'number', 'upper', 'lower', 'symbol'].filter((r) => this.passwordRule(r)).length;
            },
            passwordLabel() {
                const score = this.passwordScore();
                if (score >= 5) return 'Fuerte';
                if (score >= 3) return 'Media';
                return 'Débil';
            },
        };
    }
</script>
@endpush
@endonce
