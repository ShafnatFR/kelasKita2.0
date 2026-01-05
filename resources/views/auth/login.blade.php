<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KelasKita 2.0</title> {{-- UBAH 1: Judul Halaman --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=cal:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full">
    <div class="min-h-screen flex">

        <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center p-12 overflow-hidden">
            <div
                class="absolute top-0 -left-4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-32 left-20 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
            </div>

            <div class="relative z-10 text-center max-w-lg">
                <div class="mb-8">
                    {{-- Logo Placeholder or Icon --}}
                    <div
                        class="inline-flex items-center justify-center p-3 bg-white/10 rounded-xl backdrop-blur-md border border-white/10 mb-6 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h1 class="text-5xl font-extrabold tracking-tight text-white mb-6 drop-shadow-sm">
                        KelasKita<span class="text-blue-400">.</span>
                    </h1>
                    {{-- UBAH 2: Text Deskripsi lebih ke arah User/Student --}}
                    <p class="text-lg text-slate-300 leading-relaxed font-light">
                        Start your learning journey today. Access world-class materials, connect with expert mentors, and achieve your goals.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-6 text-center">
                    <div
                        class="p-4 rounded-xl bg-white/5 backdrop-blur-sm border border-white/5 hover:bg-white/10 transition duration-300">
                        <div class="text-2xl font-bold text-white mb-1">24k+</div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Active Students</div>
                    </div>
                    <div
                        class="p-4 rounded-xl bg-white/5 backdrop-blur-sm border border-white/5 hover:bg-white/10 transition duration-300">
                        <div class="text-2xl font-bold text-white mb-1">150+</div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Expert Mentors</div>
                    </div>
                    <div
                        class="p-4 rounded-xl bg-white/5 backdrop-blur-sm border border-white/5 hover:bg-white/10 transition duration-300">
                        <div class="text-2xl font-bold text-white mb-1">99%</div>
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Satisfaction</div>
                    </div>
                </div>
            </div>

            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150">
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 bg-white relative">
            <div class="w-full max-w-[400px]">
                <div class="text-center lg:hidden mb-10">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">KelasKita<span
                            class="text-blue-600">.</span></h1>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Welcome Back!</h2>
                    <p class="text-slate-500">
                        Please enter your credentials to access your account.
                    </p>
                </div>

                {{-- Error Alerts --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100 flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-red-800">Authentication Error</h3>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100 flex items-center gap-3">
                        <svg class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                {{-- UBAH 3: Action mengarah ke route login user biasa, bukan admin --}}
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Email
                            Address</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-lg border-0 py-3.5 pl-10 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 focus:bg-white sm:text-sm sm:leading-6 transition-all duration-200 outline-none shadow-sm"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password"
                                class="block text-sm font-semibold leading-6 text-slate-700">Password</label>
                            
                            {{-- UBAH 4: Link Forgot Password biasanya aktif untuk user biasa --}}
                            <div class="text-sm">
                                <a href="#" {{-- Masukkan route('password.request') jika ada --}}
                                    class="font-medium text-blue-600 hover:text-blue-500 hover:underline">Forgot
                                    password?</a>
                            </div>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border-0 py-3.5 pl-10 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 focus:bg-white sm:text-sm sm:leading-6 transition-all duration-200 outline-none shadow-sm"
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative flex w-full justify-center rounded-xl bg-blue-600 px-3 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-none">
                            Sign In
                            <svg class="ml-2 -mr-0.5 h-5 w-5 text-blue-100 group-hover:translate-x-1 transition-transform"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- UBAH 5: Link Register (User biasa butuh ini, Admin tidak) --}}
                <p class="mt-6 text-center text-sm text-slate-600">
                    Don't have an account?
                    <a href="/register" {{-- Masukkan route('register') disini --}}
                       class="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-colors">
                        Sign up now
                    </a>
                </p>

                <p class="mt-6 text-center text-sm text-slate-400">
                    &copy; 2026 KelasKita. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>

</html>