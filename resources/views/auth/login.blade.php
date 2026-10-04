<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-xs text-slate-500 mt-1">Masuk ke ruang kerja Research OS Anda untuk melanjutkan riset.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
            <input id="email" class="block w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 shadow-2xs py-2.5 px-3"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] text-teal-600 hover:text-teal-700 hover:underline font-medium" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <input id="password" class="block w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 shadow-2xs py-2.5 px-3"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-teal-600 shadow-2xs focus:ring-teal-500" name="remember">
                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 hover:opacity-95 shadow-md shadow-teal-700/20 active:scale-[0.99] transition flex items-center justify-center space-x-2">
                <span>Masuk ke Research OS</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
