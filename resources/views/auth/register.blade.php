<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - KelasKita 2.0</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="h-full">
    <div class="min-h-screen flex">

        <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center p-12 overflow-hidden">
            <div class="absolute top-0 -left-4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

            <div class="relative z-10 text-center max-w-lg">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center p-3 bg-white/10 rounded-xl backdrop-blur-md border border-white/10 mb-6 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h1 class="text-5xl font-extrabold tracking-tight text-white mb-6">
                        Join Us<span class="text-blue-400">.</span>
                    </h1>
                    <p class="text-lg text-slate-300 leading-relaxed font-light">
                        Create an account to unlock your potential. Become a student to learn new skills, or a mentor to share your knowledge.
                    </p>
                </div>
            </div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 bg-white relative overflow-y-auto">
            <div class="w-full max-w-[500px]"> {{-- Lebar sedikit ditambah agar form muat --}}
                
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 mb-2">Create an Account</h2>
                    <p class="text-slate-500">Sign up in less than 2 minutes.</p>
                </div>

                {{-- Error Alerts --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100">
                        <h3 class="text-sm font-semibold text-red-800 mb-1">Registration Failed</h3>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="fullname" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Full Name</label>
                            <input id="fullname" name="fullname" type="text" required value="{{ old('fullname') }}"
                                class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all"
                                placeholder="John Doe">
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Username</label>
                            <input id="username" name="username" type="text" required value="{{ old('username') }}"
                                class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all"
                                placeholder="johndoe123">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all"
                            placeholder="name@example.com">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">I want to be a...</label>
                        <div class="relative">
                            <select id="role" name="role" class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all appearance-none">
                                <option value="student">Student (Saya ingin belajar)</option>
                                <option value="mentor">Mentor (Saya ingin mengajar)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Password</label>
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all"
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold leading-6 text-slate-700 mb-1">Confirm</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 bg-slate-50 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm outline-none transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="group relative flex w-full justify-center rounded-xl bg-blue-600 px-3 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 hover:shadow-blue-500/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-none">
                            Sign Up
                            <svg class="ml-2 -mr-0.5 h-5 w-5 text-blue-100 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 hover:underline transition-colors">
                        Sign In
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